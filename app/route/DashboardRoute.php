<?php
use App\Lib\Auth,
    App\Lib\Response,
    App\Lib\GeneralFunction,
    App\Validation\wifiValidation,
    App\Middleware\AuthMiddleware;



$app->group('/dashboard', function () {

$this->get('', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $id= Auth::GetData($token)->id;
    //echo "Aqui coño ".$id;
   $audits = $this->controller->audit->getAuditsByUserId($id);
    //var_dump($audits);
   if($type_petition == "html"){
    return $this->view->render($response, 'templates/dashboard.twig',[
        'audits' => $audits,
        'locale' => $request->getAttribute('locale'),
      ]);

    }else{
        return $response->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($audits)
        ); 
    }
    
})->add(new AuthMiddleware($this));


});