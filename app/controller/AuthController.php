<?php
namespace App\Controller;

use App\Lib\Response,
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

					$this->response->result = $token;

					return $this->response->SetResponse(true);
				} else{
					return $this->response->SetResponse(false, "Credenciales no válidas");
				}
			}else{
				return $this->response->SetResponse(false, "Activa tu cuenta");
			}
		}else{
			return $this->response->SetResponse(false, "Credenciales no válidas");
		}
	}

}