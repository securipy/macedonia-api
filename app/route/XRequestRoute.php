<?php
use App\Lib\Auth,
    App\Lib\Response,
    App\Lib\GeneralFunction,
    App\Validation\XRequestValidation,
    App\Middleware\AuthMiddleware;


$app->group('/xrequest/', function () {
	    $this->post('new', function ($req, $res, $args) {
        $expected_fields = array('domain','max_request','type');

        $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

        $r = XRequestValidation::Validate($data);

         if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }

       return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->xrequest->setXRequest($data))
        ); 

    });

	$this->put('update', function ($req, $res, $args) {
        $expected_fields = array('domain','max_request','type','id');

        $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

        $r = XRequestValidation::Validate($data,'update');

         if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }

       return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->xrequest->UpdateXRequest($data))
        ); 

    });

    $this->delete('delete', function ($req, $res, $args) {
        $expected_fields = array('domain','id');

        $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

        $r = XRequestValidation::Validate($data,'delete');

         if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }

       return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->xrequest->DeleteXrequestDomain($data))
        ); 

    });


});