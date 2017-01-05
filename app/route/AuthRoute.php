<?php
use App\Lib\Auth,
    App\Lib\Response,
    App\Lib\GeneralFunction,
    App\Validation\authValidation,
    App\Middleware\AuthMiddleware;

$app->group('/auth/', function () {
    /**
     * @api {post} /auth/login
     * @apiName login
     * @apiGroup auth
     * @apiDescription Route to login user in the platform
     * @apiVersion 0.0.1
     * 
     * @apiParam {string} email email to login.
     * @apiParam {string} password password to login.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       {
     *           "result": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE0NjkxNjY2NDEsImF1ZCI6ImMyZTJhMzU3MTM2NTcwNGFlNGU3YjU0YTRiM2JiMzFiNDc5YjZiMDkiLCJkYXRhIjp7ImlkIjoiMTYiLCJuYW1lIjoicGVkcm8yIiwiZ3JvdXAiOiIxIn19.YJjTcE7DgUASmhPCCv_PKUq4FFDHDS_N7qHx_6Ty6Io"
     *           "response": true
     *           "message": ""
     *           "errors": [0]
     *           }
     *     }
     * @apiSuccessExample {json} Unprocessable Entity:
     *     HTTP/1.1 422 Unprocessable Entity
     *     {
     *       {
     *        - "password": [1]
     *          0:  "Este campo es obligatorio"
     *        - "email": [1]
     *          0:  "Este campo es obligatorio"
     *       }
     *     }
     *
     * 
     */
    $this->post('login', function ($req, $res, $args) {
        
        $expected_fields = array('email','password');

        $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);
       
         $r = authValidation::Validate($data);
        
         if(!$r->response){
            return $res->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($r->errors));
        }



        return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->controller->auth->login($data))
                   );
    });

    $this->get('login', function ($request, $response, $args) {
        return $this->view->render($response, 'templates/login.twig');
    });


});