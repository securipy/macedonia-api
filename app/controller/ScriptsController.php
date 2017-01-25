<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Language,
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

	public function getScriptsEnabled($id,$scripts)
	{
		if(!empty($scripts)){
			$allscripts = array();
			foreach ($scripts as $key => $value) {
				$data = $this->model->getScriptsEnabled($id,$value);
				if($data->response == True && !empty($data->result)){
					array_push($allscripts, $data->result);
				}
			}
		}
		$this->response->result = $allscripts;
		return $this->response->SetResponse(true,Language::_f("script data"));
	}


	public function getServerScripts($id,$module)
	{
		return $this->model->getServerScripts($id,$module);
	}

	public function getScriptsByModule($module)
	{
		return $this->model->getScriptsByModule($module);
	}


}