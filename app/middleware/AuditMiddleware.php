<?php
namespace App\Middleware;

use Exception,
    App\Lib\Auth,
    App\Lib\Response;

class AuditMiddleware
{
    private $app = null;
    private $response;
    
    public function __construct($app){
        $this->app = $app;    
        $this->response = new Response();

    }
    
    public function __invoke($request, $response, $next){
        $token = $request->getAttribute('token');
        $type_petition = $request->getAttribute('type_petition');
        $id_user = Auth::GetData($token)->id;
        if($type_petition=='html'){
            $audit = $_COOKIE['audit'];
            if(isset($audit) && !empty($audit)){
                $request = $request->withAttribute('audit', $audit);
            }else{
                return $response->withStatus(400)
                            ->write('Cookie audit not found');
            }
        }else{
            $audit = $request->getHeader('audit')[0];
            if(isset($audit) && !empty($audit)){
                $request = $request->withAttribute('audit', $audit);
            }else{
                return $response->withStatus(400)
                            ->write('Header audit not found');
            }        
        }

        $result_audit_user = $this->checkAuditByUser($id_user,$audit);

        if($result_audit_user->permision == 0){
            return $response->withStatus(403)
                    ->write('Unauthorized');
        }elseif ($result_audit_user->permision == -1) {
            return $response->withStatus(500)
                            ->write('Internal Error');
        }else{
            return $next($request, $response);   
        }
        


        
    }

    private function checkAuditByUser($id_user,$id_audit){
        $c = $this->app->getContainer();
        $st = $c->db->prepare("SELECT COUNT(*) as permision FROM audit WHERE id=:id AND id_user=:id_user");

        $st->bindParam(':id',$id_audit);
        $st->bindParam(':id_user',$id_user);
        
        
        if($st->execute()){
            $result = $st->fetch();
            return $result;
        }else{
            return -1;
        }


    }
}