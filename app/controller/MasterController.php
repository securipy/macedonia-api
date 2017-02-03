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

	public function setFileDatabase($file_name,$id_insert,$table)
	{
		$code = md5(uniqid().$file_name.$id_insert);
		return $this->model->setFileDatabase($code,$file_name,$id_insert,$table);
	}

	public function download($code,$id_audit,$id_user)
	{
		$file = clone($this->model->getFile($code));
		if($file->response == True and !empty($file->result)){
			$check_file_user = $this->model->checkFileUser($file->result->table_file,$file->result->id_table_file,$id_audit,$id_user);
			if($check_file_user->response == True){
				if($check_file_user->result->valid){
					$this->response->result = __DIR__."/../../uploads/".$file->result->file;
					return $this->response->SetResponse(True,"File");
				}else{
					$this->response->result = null;
					return $this->response->SetResponse(False,"File not valid Not is Your file");
				}
			}else{
				$this->response->result = null;
				return $this->response->SetResponse(False,"File not valid error check");
			}
		}else{
			$this->response->result = null;
			return $this->response->SetResponse(False,"File not valid");
		}
	}


	public function downloadApp($code,$id_server)
	{
		$file = clone($this->model->getFile($code));
		if($file->response == True and !empty($file->result)){
			$check_file_user = $this->model->checkFileServer($file->result->table_file,$file->result->id_table_file,$id_server);
			if($check_file_user->response == True){
				if($check_file_user->result->valid){
					$this->response->result = __DIR__."/../../uploads/".$file->result->file;
					return $this->response->SetResponse(True,"File");
				}else{
					$this->response->result = null;
					return $this->response->SetResponse(False,"File not valid Not is Your file");
				}
			}else{
				$this->response->result = null;
				return $this->response->SetResponse(False,"File not valid error check");
			}
		}else{
			$this->response->result = null;
			return $this->response->SetResponse(False,"File not valid");
		}
	}



	//TODO: Add filters type file, size... 
	public function uploadFile($file,$name)
	{
		if (!empty($file[$name])) {
            $newfile = $file[$name];
            if ($newfile->getError() === UPLOAD_ERR_OK) {
				$namefile = md5(uniqid());
                $uploadFileName = $newfile->getClientFilename();
                $parts = explode('.', $uploadFileName);
                $namefile .= ".".$parts['1'];
                try {
                    if ($newfile->moveTo("../uploads/$namefile")) {
                        throw new \Exception();
                    }                    
                } catch (\Exception $e) {
                    return $this->response->SetResponse(false,$e->getMessage());
                }
                $this->response->result = $namefile;
                return $this->response->SetResponse(True,Language::_f('upload correct'));            
            }
        }else{
                return $this->response->SetResponse(false,Language::_f("nothing to upload"));            
        }
	}


}