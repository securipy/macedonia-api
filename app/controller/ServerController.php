<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;


class ServerController extends MasterController
{
	private $response;

	public function __construct($model,$connection)
	{
		$this->response = new Response();
		$this->model = $model;
		parent::__construct($connection,$this->response);

	}


	public function getServerByIdUser($id_user)
	{
		return $this->model->getServerByIdUser($id_user);
	}


	public function getServerById($id_server)
	{
		return $this->model->getServerById($id_server);
	}

	public function getServerScriptById($id_user,$id_server)
	{
		return $this->model->getServerScriptById($id_user,$id_server);
	}

	public function deleteServerScripts($id_server)
	{
		return $this->model->deleteServerScripts($id_server);
	}


	public function updateServer($id_user,$id_server,$name,$ip_domain,$scripts)
	{

		$result_server = $this->getServerScriptById($id_user,$id_server);
		if($result_server->response == true && !empty($result_server->result)){
			$result_update_server = $this->model->updateServer($id_user,$id_server,$name,$ip_domain);
			if($result_update_server->response == true){
				//TODO: Check why rowCount return 0 not 1
				//if($result_update_server->response == true && !empty($result_update_server->result)){
				$result_delete = $this->deleteServerScripts($id_server);
				//return $result_delete;
				if($result_delete->response == true){
					$result_script = $this->setServerScripts($id_server,$scripts);
					$result_server = $this->getServerScriptById($id_user,$id_server);
					return $result_server;
				}else{
					return $result_delete;
				}
			}else{
				return $result_update_server;
			}
		}else{
			$this->response->result = null;
			$this->response->setResponse(False,"This not is your server");
		}
	}

	public function setServerByUser($id_user,$name,$ip_domain,$scripts)
	{
		$result_set_server = $this->model->setServerByUser($id_user,$name,$ip_domain);
		if($result_set_server->response == true && !empty($result_set_server->result)){
			$id = $result_set_server->result;
			$result_script = $this->setServerScripts($result_set_server->result,$scripts);
			$result_token = $this->getServerById($id);
			return $result_token;
		}else{
			return $result_set_server;
		}
	}

	public function setServerScripts($id_server,$scripts)
	{
		$error_scripts =array();
		//var_dump($id_server);
		foreach ($scripts as $key => $value) {
			$result_scripts = $this->model->setServerScripts($id_server,$value);
			if($result_scripts->response == false){
				$error_scripts[] = $result_scripts;
			}
		}
		if(empty($error_scripts)){
			$this->response->result = null;
			return $this->response->setResponse(true);
		}else{
			$this->response->error = $error_scripts;
			$this->response->setRespose(false);
		}
	}


	public function deleteServer($id_user,$id_server)
	{
		return $this->model->deleteServer($id_user,$id_server);

	}

}