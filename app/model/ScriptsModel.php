<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Auth;

class ScriptsModel
{
    private $db;
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }



    public function getScripts()
    {

        $sql = "SELECT * FROM scripts";

        $st = $this->db->prepare($sql);

        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,"Script data");
        }else{
            return $this->response->SetResponse(false,"Error get Scripts");
        }
    }



    public function getScriptsEnabled($id,$script)
    {

        $sql = "SELECT ss.id_server_script,sv.name as name_server,sv.ip_domain,sv.id as id_server FROM servers as sv,scripts as sc, servers_scripts as ss WHERE sv.id_user=:id AND sv.id = ss.id_server AND ss.id_scripts = sc.id AND sc.name=:script ";

        $st = $this->db->prepare($sql);
        $st->bindParam(':id',$id);
        $st->bindParam(':script',$script);


        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,"Script data");
        }else{
            return $this->response->SetResponse(false,"Error get Scripts");
        }
    }



}