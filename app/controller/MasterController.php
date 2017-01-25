<?php
namespace App\Controller;

use App\model\MasterModel,
App\Lib\Language;


class MasterController
{

	private $response;
	private $model;
	

	public function __construct($model,$response)
	{
		$this->response = $response;
		$this->model = new MasterModel($model,$response);

	}


	public function checkServerScriptByUser($id_user,$id_server,$name_scripts)
	{
		return $this->model->checkServerScriptByUser($id_user,$id_server,$name_scripts);
	}


	public function setScriptServerWork($id,$id_device,$id_script_server,$day_scan)
	{
		return $this->model->setScriptServerWork($id,$id_device,$id_script_server,$day_scan);
	}


	public function getWorksById($id_work)
	{
		return $this->model->getWorksById($id_work);
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
                return $this->response->SetResponse(True,Language::_f('upload correct'));            
            }
        }else{
                return $this->response->SetResponse(false,Language::_f("nothing to upload"));            
        }
	}


}