<?php
use App\Lib\Auth,
    App\Lib\Response,
    App\Lib\GeneralFunction,
    App\Validation\userValidation,
    App\Middleware\AuthMiddleware;


$app->group('/user/', function () {
    /**
     * @api {post} /user/register 
     * @apiGroup user
     * @apiName Register
     * @apiDescription Route to create new user in the platform
     * @apiVersion 0.0.1
     * 
     * @apiParam {string} name name to register.
     * @apiParam {string} email email to register.
     * @apiParam {string} password password to register.
     * @apiParam {string} repassword repassword to register.
     *
     *
     * @apiSuccessExample {json} Success-Response
     *     HTTP/1.1 200 OK
     *     {
     *       {
     *           "result": ""
     *           "response": true
     *           "message": ""
     *           "errors": [0]
     *           }
     *     }
     * @apiSuccessExample {json} Unprocessable Entity
     *     HTTP/1.1 422 Unprocessable Entity
     *     {
     *       {
     *
     *        - "name": [1]
     *          0:  "Este campo es obligatorio"
     *        - "email": [1]
     *          0:  "Este campo es obligatorio"
     *        - "password": [1]
     *          0:  "Este campo es obligatorio"
     *        - "repassword": [1]
     *          0:  "Este campo es obligatorio"
     *       }
     *     }
     *
     * 
     */
    $this->post('register', function ($req, $res, $args) {


        $expected_fields = array('name','email','password','repassword');

        $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

        $r = userValidation::Validate($data);

         if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }

       return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->user->register($data,$this->mail))
        ); 

    });

   /**
     * @api {put} /user/update
     * @apiGroup user
     * @apiName Update
     * @apiDescription Route to update name or password
     * @apiVersion 0.0.1
     * @apiHeader {String} access-key Users unique access-key.
     *
     * 
     * @apiParam {string} name name to register.
     * @apiParam {string} password password to register.
     * @apiParam {string} repassword repassword to register.
     *
     *
     * @apiSuccessExample {json} Success-Response
     *     HTTP/1.1 200 OK
     *     {
     *       {
     *           "result": ""
     *           "response": true
     *           "message": ""
     *           "errors": [0]
     *        }
     *     }
     * @apiSuccessExample {json} Unprocessable Entity
     *     HTTP/1.1 422 Unprocessable Entity
     *     {
     *       {
     *
     *        - "name": [1]
     *          0:  "Este campo es obligatorio"
     *        - "password": [1]
     *          0:  "Este campo es obligatorio"
     *        - "repassword": [1]
     *          0:  "Este campo es obligatorio"
     *       }
     *     }
     *     
     * @apiSuccessExample {json} Unauthorized
     *     HTTP/1.1 401 Unauthorized
     *     {
     *        {
     *         "result": null
     *         "response": false
     *         "message": "Unauthorized token not valid"
     *         "errors": [0]
     *         }
     *     }
     *
     *
     * 
     */
   $this->put('update', function ($req, $res, $args) {
        $expected_fields = array('name','password','repassword');

        $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);
        
        $r = userValidation::Validate($data,true);
        
        
        if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }

        return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->user->update($data,$token = $req->getHeader($this->settings['app_token_name'])[0]))
                   ); 
    })->add(new AuthMiddleware($this));


    /**
     * @api {post} /recovery/ Recovery password
     * @apiName recovery
     * @apiGroup user
     *
     * @apiParam {String} email Users unique email.
     *
     * @apiSuccess {String} Return if email to recovery send correctly.
     */
   $this->post('recovery', function ($req, $res, $args) {
        $expected_fields = array('email');

        $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

        $r = userValidation::recovery($data);
        
        if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }
        
        return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->user->recovery($data))
                   ); 
    }); 

    /**
     * @api {post} /recovery/{token} Recovery password
     * @apiName updaterecovery
     * @apiGroup user
     *
     * @apiParam {String} Token send email before.
     *
     * @apiSuccess {String} Return if token is valid to recovery password.
     */
   $this->post('updaterecovery/{token}', function ($req, $res, $args) {


        $r = userValidation::updateRecovery($req->getParsedBody(),$args['token']);
        
        if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }
        
        return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->user->recovery($req->getParsedBody(),$args['token']))
                   ); 
    }); 
}); 