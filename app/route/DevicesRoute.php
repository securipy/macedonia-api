<?php
use App\Lib\Auth,
App\Lib\Response,
App\Lib\GeneralFunction,
App\Validation\devicesValidation,
App\Middleware\AuthMiddleware,
App\Middleware\AuditMiddleware;



$app->group('/device', function () {

  $this->get('/list', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $audit = $request->getAttribute('audit');
    $id= Auth::GetData($token)->id;

    $devices = $this->controller->devices->getDevicesByIdUserAudit($id,$audit);
    $scripts = $this->controller->scripts->getScriptsByModule('device');
    if($type_petition == "html"){
      return $this->view->render($response, 'templates/deviceslist.twig',[
        'devices' => $devices,
        'locale' => $request->getAttribute('locale'),
        'scripts' => $scripts,
        ]);

    }else{
      return $response->withHeader('Content-type', 'application/json')
      ->write(
       json_encode($devices)
       ); 
    }
    
  })->add(new AuditMiddleware($this))->add(new AuthMiddleware($this));




  $this->post('/new', function ($request, $response, $args) {
    $audit = $request->getAttribute('audit');
    
    $expected_fields = array('ip_domain');

    $data = GeneralFunction::createNullData($request->getParsedBody(),$expected_fields);

    $r = devicesValidation::Validate($data);


    if(!$r->response){
      return $res->withHeader('Content-type', 'application/json')
      ->withStatus(422)
      ->write(json_encode($r));
    }


    $devices = $this->controller->devices->setScanDevices($audit,$data['ip_domain']);
    

    return $response->withHeader('Content-type', 'application/json')
    ->write(
      json_encode($devices)
      ); 
    
    
  })->add(new AuditMiddleware($this))->add(new AuthMiddleware($this));



  $this->delete('/{id:[0-9]+}', function ($request, $response, $args) {
    $token = $request->getAttribute('token');

    $id= Auth::GetData($token)->id;
    $servers = $this->controller->devices->deleteDevice($args['id'],$id);

    return $response->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($servers)
     );
    
  })->add(new AuditMiddleware($this))->add(new AuthMiddleware($this));



});