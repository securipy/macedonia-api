<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth,
\Aws\Ec2\Ec2Client;


class DevicesController extends MasterController
{
	private $response;

	public function __construct($model,$connection)
	{
		$this->response = new Response();
		$this->model = $model;
		parent::__construct($connection,$this->response);
	}

	public function getDevicesByIdUserAudit($id_user,$id_audit)
	{
		return $this->model->getDevicesByIdUserAudit($id_user,$id_audit);
	}

	public function getDataByIdDeviceUser($id_user,$audit,$id_device)
	{
		return $this->model->getDataByIdDeviceUser($id_user,$audit,$id_device);

	}

	public function setScanDevices($audit,$ip_domain)
	{
		return $this->model->setScanDevices($audit,$ip_domain);
	}


	public function deleteDevice($id,$id_user)
	{
		return $this->model->deleteDevice($id,$id_user);
	}


}