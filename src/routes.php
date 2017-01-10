<?php
use App\Lib\Auth;
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
    