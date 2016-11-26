<?php
namespace App\Controller;

use App\model\MasterModel;


class MasterController
{

	private $response;
	private $model;
	

	public function __construct($model,$response)
	{
		$this->response = $response;
		$this->model = new MasterModel($model,$response);

	}


	public function checkServerScriptByUser($id_user,$id_server,$id_script)
	{
		return $this->model->checkServerScriptByUser($id_user,$id_server,$id_script);
	}


	//TODO: Add filters type file, size... 
	public function uploadFile($file,$name)
	{
		if (!empty($file[$name])) {
            $newfile = $file[$name];
            if ($newfile->getError() === UPLOAD_ERR_OK) {
                $uploadFileName = $newfile->getClientFilename();
                try {
                    if ($newfile->moveTo("../uploads/$uploadFileName")) {
                        throw new \Exception();
                    }                    
                } catch (\Exception $e) {
                    return $this->response->SetResponse(false,$e->getMessage());
                }
                $this->response->result = $uploadFileName;
                return $this->response->SetResponse(True,"Upload correct");            
            }
        }else{
                return $this->response->SetResponse(false,"Nothing to Upload");            
        }
	}


}