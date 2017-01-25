<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;


class DashboardController extends MasterController
{
	private $response;

	public function __construct($model,$connection)
	{
		$this->response = new Response();
		$this->model = $model;
		parent::__construct($connection,$this->response);

	}


	public function getAuditByIdUser($id)
	{
		return $this->model->getAuditByIdUser($id);
	}



}