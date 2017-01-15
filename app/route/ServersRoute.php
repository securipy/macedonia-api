<?php
use App\Lib\Auth,
App\Lib\Response,
App\Lib\GeneralFunction,
App\Validation\serverValidation,
App\Middleware\AuthMiddleware;



$app->group('/server/', function () {

  $this->get('list', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $id= Auth::GetData($token)->id;
    $servers = $this->controller->server->getServerByIdUser($id);
    var_dump($servers);
    if($type_petition == "html"){
      return $this->view->render($response, 'templates/listservers.twig',[
        'servers' => $servers,
        'locale' => $request->getAttribute('locale'),
        ]);
    }else{
     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($servers)
       );
   }

 })->add(new AuthMiddleware($this));


  $this->get('{id:[0-9]+}', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $id= Auth::GetData($token)->id;
    $servers = $this->controller->server->getServerScriptById($id,$args['id']);

    return $response->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($servers)
     );
    
  })->add(new AuthMiddleware($this));


  $this->delete('{id:[0-9]+}', function ($request, $response, $args) {
    $token = $request->getAttribute('token');

    $id= Auth::GetData($token)->id;
    $servers = $this->controller->server->deleteServer($id,$args['id']);

    return $response->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($servers)
     );
    
  })->add(new AuthMiddleware($this));




  $this->put('update/{id:[0-9]+}', function ($request, $response, $args) {
    $expected_fields = array('name','ip_domain','scripts');

    $data = GeneralFunction::createNullData($request->getParsedBody(),$expected_fields);

    $r = serverValidation::Validate($data);

    $token = $request->getAttribute('token');

    $type_petition = $request->getAttribute('type_petition');
    
    $id_user= Auth::GetData($token)->id;
    
    $servers = $this->controller->server->updateServer($id_user,$args['id'],$data['name'],$data['ip_domain'],$data['scripts']);

    return $response->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($servers)
     );
    

  })->add(new AuthMiddleware($this));



  $this->post('new', function ($request, $response, $args) {
    $expected_fields = array('name','ip_domain','scripts');

    $data = GeneralFunction::createNullData($request->getParsedBody(),$expected_fields);

    $r = serverValidation::Validate($data);

    $token = $request->getAttribute('token');

    $type_petition = $request->getAttribute('type_petition');
    
    $id_user= Auth::GetData($token)->id;
    
    $servers = $this->controller->server->setServerByUser($id_user,$data['name'],$data['ip_domain'],$data['scripts']);

    if($type_petition == "html"){
      return $this->view->render($response, 'listservers.twig',[
        'servers' => $servers,
        'locale' => $request->getAttribute('locale'),
        ]);
    }else{
     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($servers)
       );
   }

 })->add(new AuthMiddleware($this));




});