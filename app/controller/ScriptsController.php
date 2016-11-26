<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;


class ScriptsController
{
	private $response;

	public function __construct($model)
	{
		$this->response = new Response();
		$this->model = $model;
	}


	public function getScripts()
	{
		return $this->model->getScripts();
	}

	public function getScriptsEnabled($id,$script)
	{
		return $this->model->getScriptsEnabled($id,$script);
	}

}