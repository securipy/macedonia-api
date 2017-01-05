<?php
namespace App\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
* Middleware Language url
*/
class LanguageMiddleware
{
    
    private $app = null;
    private $language;
    
    public function __construct($app){
        $this->app = $app;    
        $this->language = $this->app->getContainer()->get('settings')['languages'];
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        //error_log(var_dump(__DIR__));

        $uri = $request->getUri();
        $virtualPath = $uri->getPath();
        $pathChunk = explode("/",$virtualPath);
        if(count($pathChunk) > 1 && array_key_exists($pathChunk[1], $this->language)) {
            $language = $pathChunk[1];
            $pathChunk = array_slice($pathChunk, 2);
            $path = "/".implode("/",$pathChunk);
            $uri = $uri->withPath($path);
            $request = $request->withUri($uri);
            $locale = $this->language[$language];
            $dir = __DIR__."/../locale/";
            setlocale(LC_ALL, $locale);
            bindtextdomain("index", $dir);
            textdomain("index");
        }else{
            $dir = __DIR__."/../locale/";
            $locale = "en_GB.UTF-8";
            setlocale(LC_ALL, $locale);
            bindtextdomain("index", $dir);
            textdomain("index");
        }
        return $next($request, $response);
    }


}