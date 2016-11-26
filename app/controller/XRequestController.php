<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;

class XRequestController
{

	private $response;

	public function __construct($model)
	{
		$this->response = new Response();
		$this->model = $model;
	}


	public function setXRequest($data)
	{

		$resultCheckEmail = $this->model->getFromDomain($data['domain']);

		if(!is_array($resultCheckEmail)){
			if(!$resultCheckEmail->count_domain > 0){
				$correct_set = $this->model->setXrequestDomain($data['domain'],$data['max_request'],$data['type']);
				return $this->response->SetResponse($correct_set[0], $correct_set[1]);
			}else{
				return $this->response->SetResponse(false, "Dominio ya registrado");
			}

			return $this->response->SetResponse(true);
		}else{
			return $this->response->SetResponse($resultCheckEmail[0], $resultCheckEmail[1]);
		}



	}


	public function UpdateXRequest($data)
	{
		$correct_set = $this->model->UpdateXrequestDomain($data['domain'],$data['max_request'],$data['type'],$data['id']);
		return $this->response->SetResponse($correct_set[0], $correct_set[1]);
	}

	public function DeleteXrequestDomain($data)
	{
		$correct_set = $this->model->DeleteXrequestDomain($data['domain'],$data['id']);
		return $this->response->SetResponse($correct_set[0], $correct_set[1]);
	}


}

?>