<?php
namespace App\Middleware;

use Exception,
    App\Lib\Auth,
    FastRoute\RouteParser,
    FastRoute\RouteParser\Std as StdParser;

class PermisionMiddleware
{
    private $app = null;
    
    public function __construct($app){
        $this->app = $app;    
        $this->checkRoute = new StdParser;
    }
    
    public function __invoke($request, $response, $next){
        
        $c = $this->app->getContainer();
        
        $url = $request->getAttribute('route')->getPattern();

        $app_token_name = $c->settings['app_token_name'];
        
        $token = $request->getHeader($app_token_name);
        
        if(isset($token[0])) $token = $token[0];
        
        $data = Auth::GetData($token);

        $st = $c->db->prepare("SELECT COUNT(*) as permision FROM user_url_access WHERE url=:url AND group_user=:group_user AND method=:method");

        $method = $request->getMethod();

        $st->bindParam(':url',$url);
        $st->bindParam(':group_user',$data->group);
        $st->bindParam(':method',$method);
        
        
        if($st->execute()){
            $result = $st->fetch();
            if($result->permision == 0){
                return $response->withStatus(403)
                            ->write('Unauthorized');
            }else{
                return $next($request, $response);   
            }
        }else{
            return $response->withStatus(500)
                            ->write('Internal Error');
        }
    
       
        
    }
}