<?php
namespace App\Model;

use App\Lib\Language;


class MasterModel
{
    private $db;
    private $response;
    
    public function __construct($db,$response)
    {
        $this->db = $db;
        $this->response = $response;
    }


    public function getFile($code)
    {
       $st = $this->db->prepare("SELECT * FROM files WHERE code=:code");
        $st->bindParam(':code',$code);
        if($st->execute()){
            $this->response->result = $st->fetch();
            return $this->response->SetResponse(true,"Data file");
        }else{
          return $this->response->SetResponse(false,'Error data file');
        }
    }

    public function checkFileUser($table,$id_table,$id_audit,$id_user)
    {
        $st = $this->db->prepare("SELECT COUNT(*) as valid FROM ".$table." as t,audit as a WHERE t.id=:id_table AND a.id_user=:id_user AND t.id_audit=:id_audit AND t.id_audit=a.id");
        $st->bindParam(':id_table',$id_table);
        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':id_audit',$id_audit);
        if($st->execute()){
            $this->response->result = $st->fetch();
            return $this->response->SetResponse(true,"Data file");
        }else{
          return $this->response->SetResponse(false,'Error data file');
        }
    }


    public function checkFileServer($table,$id_table,$id_server)
    {
        $st = $this->db->prepare("SELECT COUNT(*) as valid FROM ".$table." as t,audit as a, servers as s WHERE t.id=:id_table AND a.id_user=s.id_user AND s.id=:id_server AND t.id_audit=a.id");
        $st->bindParam(':id_table',$id_table);
        $st->bindParam(':id_server',$id_server);
       
        if($st->execute()){
            $this->response->result = $st->fetch();
            return $this->response->SetResponse(true,"Data file");
        }else{
          return $this->response->SetResponse(false,'Error data file');
        }
    }




    public function setFileDatabase($code,$file,$id_table_file,$table_file)
    {
        $st = $this->db->prepare("INSERT INTO files (code,file,table_file,id_table_file) VALUES (:code,:file,:table_file,:id_table_file)");
        $st->bindParam(':code',$code);
        $st->bindParam(':file',$file);
        $st->bindParam(':table_file',$table_file);
        $st->bindParam(':id_table_file',$id_table_file);
        if($st->execute()){
            $this->response->result = $this->db->lastInsertId();
            return $this->response->SetResponse(true,"Insert file database");
        }else{
          return $this->response->SetResponse(false,'Error insert file database');
        }
    }


	public function checkServerScriptByUser($id_user,$id_server,$name_scripts)
	{
    
        $sql = "SELECT s.id,s.id_user,s.ip_domain,ss.id_server_script FROM servers as s, servers_scripts as ss, scripts as sc WHERE sc.name = :name_scripts AND sc.id=ss.id_scripts AND ss.id_server=s.id AND s.id_user=:id_user AND s.id=:id_server";

         $st = $this->db->prepare($sql);


        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':id_server',$id_server);
        $st->bindParam(':name_scripts',$name_scripts);
        $this->response->result = null;  
        if($st->execute()){
            $result = $st->fetch();
            if(!empty($result)){
                $this->response->result = $result;
                return $this->response->SetResponse(true,Language::_f("check server script"));
            }else{
                $this->response->result = $result;
                return $this->response->SetResponse(false,Language::_f("this is not your server"));
            }
           
        }else{
          return $this->response->SetResponse(false,Language::_f('error check server with script'));
        }

	}



    public function setScriptServerWork($id_user,$id_info_work,$id_script_server,$date_execute)
    {
        $st = $this->db->prepare("INSERT INTO script_work (id_user,id_script_server,id_info_work,date_execute) VALUES (:id_user,:id_script_server,:id_info_work,:date_execute)");
        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':id_info_work',$id_info_work);
        $st->bindParam(':id_script_server',$id_script_server);
        $st->bindParam(':date_execute',$date_execute);
        if($st->execute()){
            $this->response->result = $this->db->lastInsertId();
            return $this->response->SetResponse(true,"Insert new scan");
        }else{
          return $this->response->SetResponse(false,'Error insert new scan');
        }
    }


    public function getWorksById($id_work)
    {
        $st = $this->db->prepare("SELECT sw.id,sw.date_execute,sw.date_start,sw.date_finish,s.name,s.ip_domain FROM script_work as sw, servers as s, servers_scripts as ss WHERE sw.id=:id_work AND sw.id_script_server=ss.id_server_script AND ss.id_server=s.id");
        $st->bindParam(':id_work',$id_work);
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,"Insert new scan");
        }else{
          return $this->response->SetResponse(false,'Error insert new scan');
        }
    }


}