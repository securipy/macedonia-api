<?php
use App\Lib\Auth,
    App\Lib\Response,
    App\Lib\GeneralFunction,
    App\Validation\authValidation,
    App\Middleware\AuthMiddleware;

$app->group('/aws/', function () {

    $this->get('instances', function ($request, $response, $args) {
        $token = $request->getAttribute('token');
        $type_petition = $request->getAttribute('type_petition');
        $id= Auth::GetData($token)->id;
        $instances = $this->controller->aws->getInstancesByIdUser($id);
        if($type_petition == "html"){
            return $this->view->render($response, 'templates/awsintances.twig',[
                'instances' => $instances
              ]);
        }else{
           return $response->withHeader('Content-type', 'application/json')
                       ->write(
                         json_encode($instances)
            );
        }
    })->add(new AuthMiddleware($this));


    $this->get('instance/start/{id_instance}', function ($request, $response, $args) {
        $token = $request->getAttribute('token');       
        $id= Auth::GetData($token)->id;
        $instances_start = $this->controller->aws->startInstancesByIdInstance($id,$args['id_instance']);
        return $response->withHeader('Content-type', 'application/json')
                       ->write(
                         json_encode($instances_start)
        );


    })->add(new AuthMiddleware($this));


    $this->get('instance/stop/{id_instance}', function ($request, $response, $args) {
        $token = $request->getAttribute('token');
        
        $id= Auth::GetData($token)->id;
        $instances_stop = $this->controller->aws->stopInstancesByIdInstance($id,$args['id_instance']);
        return $response->withHeader('Content-type', 'application/json')
                       ->write(
                         json_encode($instances_stop)
        );
    })->add(new AuthMiddleware($this));



});