<?php
namespace App\Middleware;

use Exception,
    App\Lib\Auth,
    App\Lib\Response;

class TypePetitionMiddleware
{
    private $app = null;
    private $response;
    
    public function __construct($app){
        $this->app = $app;    
        $this->response = new Response();

    }
    
    public function __invoke($request, $response, $next){
        if(preg_match('/text\/html/', $request->getHeaderLine('accept'))){
            $request = $request->withAttribute('type_petition', 'html');
        }else{
            $request = $request->withAttribute('type_petition', 'json');
        }
        return $next($request, $response);
    }
}