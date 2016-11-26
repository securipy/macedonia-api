<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;


class DashboardController
{
	private $response;

	public function __construct($model)
	{
		$this->response = new Response();
		$this->model = $model;
	}


	public function getAuditByIdUser($id)
	{
		return $this->model->getAuditByIdUser($id);
	}


}