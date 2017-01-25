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
      return $this->view->render($response, 'templates/listscripts.twig',[
        'scripts' => $scripts,
        'locale' => $request->getAttribute('locale'),
      ]);
    }else{
     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($scripts)
       );
   }

 })->add(new AuthMiddleware($this));


  $this->get('list/{scripts:.*}', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $id= Auth::GetData($token)->id;
    $scripts_check = explode('/', $request->getAttribute('scripts'));
    $scripts = $this->controller->scripts->getScriptsEnabled($id,$scripts_check);

     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($scripts)
       );

 })->add(new AuthMiddleware($this));

  $this->get('server/list/{module}', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $id= Auth::GetData($token)->id;
    $scripts = $this->controller->scripts->getServerScripts($id,$args['module']);

     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($scripts)
       );

 })->add(new AuthMiddleware($this));




});