<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth,
App\Lib\Language,
\Aws\Ec2\Ec2Client;


class AwsController
{
	private $response;

	public function __construct($model)
	{
		$this->response = new Response();
		$this->model = $model;
	}


	public function LoginAWSec2($id_user)
	{
		$result_credentials = $this->model->getCredentials($id_user);
		if($result_credentials->response && !empty($result_credentials->result)){
			$ec2Client = Ec2Client::factory(array(
				'credentials' => array(
				    'key'    => $result_credentials->result[0]->aws_key,
				    'secret' => $result_credentials->result[0]->aws_secret
			    ),
			    'region' => $result_credentials->result[0]->region, // (e.g., us-east-1)
			    'version' => $result_credentials->result[0]->version
			));
			$this->response->result = $ec2Client;
			return $this->response->setResponse(true,Language::_f('login aws ec2 correct'));

			//return $ec2Client;
		}else{
			return $result_credentials;
		}
	}


	public function getInstancesByIdUser($id_user)
	{
		$result_login_aws = $this->LoginAWSec2($id_user);
		if($result_login_aws->response && !empty($result_login_aws->result)){
			$ec2Client = $result_login_aws->result;
			$result = $ec2Client->describeInstances()->get('Reservations');
			
			$this->response->result = $result;
			return $this->response->setResponse(true,Language::_f('List AWS instances'));
			//return $result;
		}else{
			return $result_login_aws;
		}

	}

	public function startInstancesByIdInstance($id_user,$id_instance)
	{

		$result_login_aws = $this->LoginAWSec2($id_user);
		if($result_login_aws->response && !empty($result_login_aws->result)){
			$ec2Client = $result_login_aws->result;
			$result = $ec2Client->startInstances(array(
		        'InstanceIds' => array($id_instance)        
		    ));
			
			$this->response->result = $result;
			return $this->response->setResponse(true,Language::_f('start aws instances'));
			//return $result;
		}else{
			return $result_credentials;
		}

	}

	public function stopInstancesByIdInstance($id_user,$id_instance)
	{

		$result_login_aws = $this->LoginAWSec2($id_user);
		if($result_login_aws->response && !empty($result_login_aws->result)){
			$ec2Client = $result_login_aws->result;
			$result = $ec2Client->stopInstances(array(
		        'InstanceIds' => array($id_instance)        
		    ));
			
			$this->response->result = $result;
			return $this->response->setResponse(true,Language::_f('start aws instances'));
			//return $result;
		}else{
			return $result_credentials;
		}

	}

}