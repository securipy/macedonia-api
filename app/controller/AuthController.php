<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Language,
App\Lib\Auth;


class AuthController
{
	private $response;

	public function __construct($model)
	{
		$this->response = new Response();
		$this->model = $model;
	}


	public function login($data)
	{
		$resultLogin = $this->model->login($data['email']);
		if(!empty($resultLogin)){
			if($resultLogin->active == 1){
				$password = hash('sha512', $data['password']. $resultLogin->salt);
				if($password === $resultLogin->password){
					$token = Auth::SignIn([
						'id' => $resultLogin->id,
						'name' => $resultLogin->name,
						'group' => $resultLogin->user_group,
						]);
					$set_token = $this->model->setToken($token,$resultLogin->id);
					if($set_token->response == True){
						$this->response->result = $token;
						return $this->response->SetResponse(true,Language::_f("log in correct"));
					}else{
						return $this->response->SetResponse(false,Language::_f("error storing token"));
					}
				} else{
					return $this->response->SetResponse(false,Language::_f("invalid credentials"));
				}
			}else{
				return $this->response->SetResponse(false,Language::_f("active account"));
			}
		}else{
			return $this->response->SetResponse(false,Language::_f("invalid credentials"));
		}
	}

	public function logout($token)
	{
		return $this->model->logout($token);
	}

}