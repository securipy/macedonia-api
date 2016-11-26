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
        if(preg_match('/text\/html/', $request->getHeaderLine('accept'))){
            if(!empty($_COOKIE['token'])){
                
                try {
                    Auth::Check($_COOKIE['token']);
                    $request = $request->withAttribute('token', $_COOKIE['token']);
                } catch(Exception $e) {
                    return $response = $response->withRedirect('/auth/login', 401);
                }
                return $next($request, $response);
            }else{
                return $response = $response->withRedirect('/auth/login', 401);
            }
        }else{
            $c = $this->app->getContainer();
            $app_token_name = $c->settings['app_token_name'];
        
            $token = $request->getHeader($app_token_name);
        
            if(isset($token[0])) $token = $token[0];
        }
    
        try {
            Auth::Check($token);
            $request = $request->withAttribute('token', $token);
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