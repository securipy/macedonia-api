<?php
use App\Lib\Auth,
App\Lib\Response,
App\Lib\GeneralFunction,
App\Validation\auditValidation,
App\Middleware\AuthMiddleware;



$app->group('/audit/', function () {

  /**
  * @api {post} /user Name new audit
  * @apiHeader {String} GRANADA-TOKEN="" Token login valid
  *
  * @apiName new
  * @apiGroup audit
  *
  * @apiParam {String} Name of the audit.
  *
  * 
  */

  $this->post('new', function ($request, $response, $args) {
    $expected_fields = array('name');

    $data = GeneralFunction::createNullData($request->getParsedBody(),$expected_fields);

    $r = auditValidation::Validate($data);

    if(!$r->response){
      return $res->withHeader('Content-type', 'application/json')
              ->withStatus(422)
              ->write(json_encode($r));
    }


    $token = $request->getAttribute('token');
    $id= Auth::GetData($token)->id;

    return $response->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($this->controller->audit->setAudit($id,$data['name']))
     ); 
    
    
  })->add(new AuthMiddleware($this));


  /**
  * @api {get} /list list all audit from this user
  * @apiHeader {String} GRANADA-TOKEN="" Token login valid
  *
  * @apiName list
  * @apiGroup audit
  * 
  */

  $this->get('list', function ($request, $response, $args) {
   
    $token = $request->getAttribute('token');
    $id= Auth::GetData($token)->id;

    return $response->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($this->controller->audit->getAuditsByUserId($id))
     ); 
    
    
  })->add(new AuthMiddleware($this));







});