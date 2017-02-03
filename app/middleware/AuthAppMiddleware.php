<?php
namespace App\Middleware;

use Exception,
App\Lib\Auth,
App\Lib\Response,
Firebase\JWT\JWT;

class AuthAppMiddleware
{
    private $app = null;
    private $response;
    
    public function __construct($app){
        $this->app = $app;    
        $this->response = new Response();

    }
    
    public function __invoke($request, $response, $next){
        $c = $this->app->getContainer();

        $ip_address = $request->getAttribute('ip_address');

        $token = $request->getHeader('app-granada');

        $sql = "SELECT * FROM servers WHERE ip_domain=:ip AND status = 1";
        $st = $c->db->prepare($sql);
        $st->bindParam(':ip',$ip_address);    

        if($st->execute()){
            $result = $st->fetch();
            if(!empty($result)){
                try {
                    $public_key = JWT::decode(
                        $token[0],
                        $result->private_key,
                        array('HS256')
                        )->public_key;
                } catch (Exception $e) {
                    return $response->withHeader('Content-type', 'application/json')->withStatus(500)
                    ->write(json_encode($e->getMessage()));
                }
            }else{
              $this->response->SetResponse(false,"Server not found");
              return $response->withHeader('Content-type', 'application/json')
                ->withStatus(404)
                ->write(
                    json_encode($this->response)
                    );  
            }

            try {
                $public_key = JWT::decode(
                    $token[0],
                    $result->private_key,
                    array('HS256')
                    )->public_key;
            } catch (Exception $e) {
                return $response->withStatus(500)
                ->write(json_encode($e->getMessage()));
            }


            if($public_key != $result->public_key){
                return $response->withHeader('Content-type', 'application/json')
                ->withStatus(401)
                ->write(
                    json_encode("Data token not valid")
                    );
            }
            $request = $request->withAttribute('id_server', $result->id);
            return $next($request, $response);
        }else{
            return $response->withStatus(500)
            ->write(json_encode('Internal Error'));
        }


    }    

}