<?php
use App\Lib\Auth,
    App\Middleware\AuthMiddleware,
    App\Middleware\AuditMiddleware,
	App\Lib\Response,
	App\Middleware\AuthAppMiddleware,
    App\controller\MasterController;
// Routes

$app->get('/', function ($request, $response, $args) {
	if(isset($_COOKIE['token']) && !empty($_COOKIE['token'])){
		try {
		    Auth::Check($_COOKIE['token']);
		} catch (Exception $e) {
		    return $response->withRedirect('/auth/login', 301);
		}
		return $response->withRedirect('/dashboard', 301);

	}else{
		return $response->withRedirect('/auth/login', 301);
	}

});
    

$app->get('/download/{code_file}', function ($request, $response, $args) {
	$token = $request->getAttribute('token');
    $audit = $request->getAttribute('audit');
    $id= Auth::GetData($token)->id;

	$response_structure = new Response();
	$MasterController = new MasterController($this->db,$response_structure);
	$file_result = $MasterController->download($args['code_file'],$audit,$id);
	if($file_result->response == True && !empty($file_result->result)){
		$file = $file_result->result;
		    $res = $response->withHeader('Content-Description', 'File Transfer')
		   ->withHeader('Content-Type', 'application/octet-stream')
		   ->withHeader('Content-Transfer-Encoding:','Binary')
		   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
		   ->withHeader('Expires', '0')
		   ->withHeader('Cache-Control', 'must-revalidate')
		   ->withHeader('Pragma', 'public')
		   ->withHeader('Content-Length', filesize($file));

			readfile($file);
			return $res;
	}else{
		return $response->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($file_result));
	}

})->add(new AuditMiddleware($app))->add(new AuthMiddleware($app));



$app->get('/download/app/{code_file}', function ($request, $response, $args) {
    $id_server = $request->getAttribute('id_server');


	$response_structure = new Response();
	$MasterController = new MasterController($this->db,$response_structure);
	$file_result = $MasterController->downloadApp($args['code_file'],$id_server);
	if($file_result->response == True && !empty($file_result->result)){
		$file = $file_result->result;
		    $res = $response->withHeader('Content-Description', 'File Transfer')
		   ->withHeader('Content-Type', 'application/octet-stream')
		   ->withHeader('Content-Transfer-Encoding:','Binary')
		   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
		   ->withHeader('Expires', '0')
		   ->withHeader('Cache-Control', 'must-revalidate')
		   ->withHeader('Pragma', 'public')
		   ->withHeader('Content-Length', filesize($file));

			readfile($file);
			return $res;
	}else{
		return $response->withHeader('Content-type', 'application/json')
                       ->withStatus(422)
                       ->write(json_encode($file_result));
	}

  })->add(new AuthAppMiddleware($app));