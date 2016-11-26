<?php
namespace App\Model;

class MasterModel
{
    private $db;
    private $response;
    
    public function __construct($db,$response)
    {
        $this->db = $db;
        $this->response = $response;
    }

	public function checkServerScriptByUser($id_user,$id_server,$id_scripts)
	{
    
        $sql = "SELECT s.id,s.id_user,s.ip_domain,ss.id_server_script FROM servers as s, servers_scripts as ss WHERE ss.id_server = s.id AND ss.id_scripts = :id_scripts AND s.id_user = :id_user AND s.id = :id_server";

         $st = $this->db->prepare($sql);


        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':id_server',$id_server);
        $st->bindParam(':id_scripts',$id_scripts);
        $this->response->result = null;  
        if($st->execute()){
            $this->response->result = $st->fetch();
            return $this->response->SetResponse(true,"Check server script");
        }else{
          return $this->response->SetResponse(false,'Error check server with script');
        }

	}



}