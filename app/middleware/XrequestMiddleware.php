<?php
namespace App\Middleware;

use Exception,
    App\Lib\Auth;

class XrequestMiddleware
{
    private $app = null;
    
    public function __construct($app){
        $this->app = $app;    
    }
    
    public function __invoke($request, $response, $next){
        
        $c = $this->app->getContainer();
        
        $url = $request->getUri()->getHost();
        return $response->withStatus(500)
                            ->write($url);
        //var_dump($url);

      /* $st = $c->db->prepare("SELECT * FROM xrequests_urls WHERE domain=:domain");

        $st->bindParam(':domain',$domain);
        //$st->bindParam(':group_user',$data->group);

        
        if($st->execute()){
            $result = $st->fetch();
            if($result->permision == 0){
                return $response->withStatus(403)
                            ->write('Unauthorized');
            }
        }else{
            return $response->withStatus(500)
                            ->write('Internal Error');
        }*/
        //$response->getBody()->write($url);
        //return $response->write($url);
        return $next($request, $response);
    }
}