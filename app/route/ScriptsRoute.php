<?php
use App\Lib\Auth,
App\Lib\Response,
App\Lib\GeneralFunction,
App\Validation\wifiValidation,
App\Middleware\AuthMiddleware;


$app->group('/scripts/', function () {

  $this->get('list', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $scripts = $this->controller->scripts->getScripts();
    if($type_petition == "html"){
      return $this->view->render($response, 'listscripts.twig',[
        'scripts' => $scripts
      ]);
    }else{
     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($scripts)
       );
   }

 })->add(new AuthMiddleware($this));


  $this->get('list/{script}', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $id= Auth::GetData($token)->id;
    $scripts = $this->controller->scripts->getScriptsEnabled($id,$args['script']);

     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($scripts)
       );

 })->add(new AuthMiddleware($this));



});