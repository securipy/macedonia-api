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
        $uri = $request->getUri();
        $virtualPath = $uri->getPath();
        $pathChunk = explode("/",$virtualPath);
        if(count($pathChunk) > 1 && array_key_exists($pathChunk[1], $this->language)) {
            $language = $pathChunk[1];
            $pathChunk = array_slice($pathChunk, 2);
            $path = "/".implode("/",$pathChunk);
            $uri = $uri->withPath($path);
            $request = $request->withUri($uri)->withAttribute('locale', $language);
            $locale = $this->language[$language];
            $this->getLocale("index",__DIR__."/../locale/",$locale);
            $this->readModules($locale);
        }else{
          $this->getLocale();
          $this->readModules();
          $request = $request->withAttribute('locale', 'en');
        }
        return $next($request, $response);
    }


    private function getLocale($domain = "index",$dir=__DIR__."/../locale/",$locale = "en_GB.UTF-8")
    {
        setlocale(LC_ALL, $locale);
        bindtextdomain($domain, $dir);
        textdomain($domain);
    }

    private function readModules($locale = "en_GB.UTF-8"){
        $path = __DIR__ . '/../modules';

        $results = scandir($path);

        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            if(is_dir($path."/".$result)){
                $this->getLocale($result,$path."/".$result."/locale/",$locale);
            }
        }
    }

}