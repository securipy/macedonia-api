<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Language,
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
            return $this->response->SetResponse(true,Language::_f("script data"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error get scripts"));
        }
    }



    public function getScriptsEnabled($id,$script)
    {

        $sql = "SELECT ss.id_server_script,sv.name as name_server,sv.ip_domain,sv.id as id_server,sc.name FROM servers as sv,scripts as sc, servers_scripts as ss WHERE sv.id_user=:id AND sv.id = ss.id_server AND ss.id_scripts = sc.id AND sc.name=:script AND sv.status = 1";

        $st = $this->db->prepare($sql);
        $st->bindParam(':id',$id);
        $st->bindParam(':script',$script);


        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,Language::_f("script data"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error get scripts"));
        }
    }



}