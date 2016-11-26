<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;


class AuditController
{
	private $response;

	public function __construct($model)
	{
		$this->response = new Response();
		$this->model = $model;
	}


	public function setAudit($id_user,$name)
	{
		$result_insert = $this->model->setAudit($id_user,$name);
		if($result_insert->response == true and $result_insert->response != 0){
			$result = $this->model->getAuditById($result_insert->result,$id_user);
			return $result;
		}else{
			return $result_insert;
		}
	}

	public function getAuditsByUserId($id)
	{
		return $this->model->getAuditsByUserId($id);
	}

}