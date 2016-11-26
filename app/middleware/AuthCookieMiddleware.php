<?php
namespace App\Middleware;

use Exception,
App\Lib\Auth,
App\Lib\Response;

class AuthCookieMiddleware
{
    private $app = null;
    private $response;
    
    public function __construct($app){
        $this->app = $app;    
        $this->response = new Response();

    }
    
    public function __invoke($request, $response, $next){
        
        if(!empty($_COOKIE['token'])){
            
            try {
                Auth::Check($_COOKIE['token']);
            } catch(Exception $e) {
                return $response = $response->withRedirect('/auth/login', 401);
            }
            return $next($request, $response);
        }else{
            return $response = $response->withRedirect('/auth/login', 401);
        }
    }
}