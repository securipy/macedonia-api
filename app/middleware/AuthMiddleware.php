<?php
namespace App\Middleware;

use Exception,
    App\Lib\Auth,
    App\Lib\Response;

class AuthMiddleware
{
    private $app = null;
    private $response;
    
    public function __construct($app){
        $this->app = $app;    
        $this->response = new Response();

    }
    
    public function __invoke($request, $response, $next){
        $c = $this->app->getContainer();
        if(preg_match('/text\/html/', $request->getHeaderLine('accept'))){
            if(!empty($_COOKIE['token'])){
                
                try {
                    Auth::Check($_COOKIE['token']);
                    $id_user = Auth::GetData($_COOKIE['token'])->id;
                    $sql = "SELECT COUNT(*) as exist_login FROM users_login WHERE id_user=:id_user AND token = :token AND DATEDIFF(NOW(),date_create) < 2";
                    $st = $c->db->prepare($sql);
                    $st->bindParam(':id_user',$id_user);
                    $st->bindParam(':token',$_COOKIE['token']);
                    if($st->execute()){
                        $result = $st->fetch();
                        
                        if($result->exist_login > 0){
                            $request = $request->withAttribute('token', $_COOKIE['token']);
                         }else{
                           // error_log(var_dump($result));
                            return $response = $response->withRedirect('/auth/login', 401);
                        }
                    }else{
                        return $response->withStatus(500)
                        ->write(json_encode('Internal Error'));
                    }



                    $request = $request->withAttribute('token', $_COOKIE['token']);
                } catch(Exception $e) {
                    return $response = $response->withRedirect('/auth/login', 401);
                }
                return $next($request, $response);
            }else{
                return $response = $response->withRedirect('/auth/login', 401);
            }
        }else{
           
            $app_token_name = $c->settings['app_token_name'];
        
            $token = $request->getHeader($app_token_name);
        
            if(isset($token[0])) $token = $token[0];
        }
    
        try {
            Auth::Check($token);
            $id_user = Auth::GetData($token)->id;
            $sql = "SELECT COUNT(*) as exist_login FROM users_login WHERE id_user=:id_user AND token = :token AND DATEDIFF(NOW(),date_create) < 2";
            $st = $c->db->prepare($sql);
            $st->bindParam(':id_user',$id_user);
            $st->bindParam(':token',$token);
            if($st->execute()){
                $result = $st->fetch();
               // error_log(var_dump($result));
                if($result->exist_login > 0){
                    $request = $request->withAttribute('token', $token);
                }else{
                    return $response->withHeader('Content-type', 'application/json')
                        ->withStatus(401)
                        ->write(
                            json_encode($this->response->SetResponse(false, "Unauthorized token not valid"))
                            ); 
                }
            }else{
                return $response->withStatus(500)
                ->write(json_encode('Internal Error'));
            }
        } catch(Exception $e) {
            return $response->withHeader('Content-type', 'application/json')
                            ->withStatus(401)
                            ->write(
                                json_encode($this->response->SetResponse(false, "Unauthorized token not valid"))
                                );
        }
        return $next($request, $response);
    }
}