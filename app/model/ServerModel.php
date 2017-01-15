<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Language,
App\Lib\Auth;

class ServerModel
{
    private $db;
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }



    public function getServerByIdUser($id_user)
    {

        $sql = "SELECT s.id,s.name,ip_domain,GROUP_CONCAT(sr.name) as scripts FROM servers as s, scripts as sr, servers_scripts as ss WHERE s.id_user=:id_user AND ss.id_server = s.id AND ss.id_scripts = sr.id AND s.status = 1 GROUP BY s.id";
  
        $st = $this->db->prepare($sql);

        $st->bindParam(':id_user',$id_user);

        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,Language::_f("server data"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error get servers"));
        }
    }

    public function getServerScriptById($id_user,$id_server)
    {
        $sql = "SELECT s.id,s.name,ip_domain,GROUP_CONCAT(sr.name) as scripts FROM servers as s, scripts as sr, servers_scripts as ss WHERE s.id=:id_server AND s.id_user=:id_user AND ss.id_server = s.id AND ss.id_scripts = sr.id AND s.status = 1 GROUP BY s.id";

        $st = $this->db->prepare($sql);

        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':id_server',$id_server);


        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,Language::_f("server data"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error get servers"));
        }
    }

    public function getServerById($id_server)
    {

        $sql = "SELECT s.id,s.name,ip_domain,public_key,private_key,GROUP_CONCAT(sr.name) as scripts FROM servers as s, scripts as sr, servers_scripts as ss WHERE s.id=:id_server AND ss.id_server = s.id AND ss.id_scripts = sr.id AND s.status = 1 GROUP BY s.id";

        $st = $this->db->prepare($sql);

        $st->bindParam(':id_server',$id_server);
        

        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,Language::_f("server data"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error get servers"));
        }
    }




    public function setServerByUser($id_user,$name,$ip_domain)
    {
        $st = $this->db->prepare("INSERT INTO servers (id_user,ip_domain,name,public_key,private_key) VALUES (:id_user,:ip_domain,:name,:public_key,:private_key)");

        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':name',$name);
        $st->bindParam(':ip_domain',$ip_domain);
        $st->bindParam(':public_key',uniqid(mt_rand(1, mt_getrandmax()), true));
        $st->bindParam(':private_key',uniqid(mt_rand(1, mt_getrandmax()), true));

        $this->response->result = null;
        if($st->execute()){
            $this->response->result =  $this->db->lastInsertId();
            return $this->response->SetResponse(true,Language::_f("delete scripts server"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error delete scripts server"));
        }
    }



    public function setServerScripts($id_server,$id_script)
    {
        $st = $this->db->prepare("INSERT INTO servers_scripts (id_server,id_scripts) VALUES (:id_server,:id_script)");

        $st->bindParam(':id_server',$id_server);
        $st->bindParam(':id_script',$id_script);

        $this->response->result = null;
        if($st->execute()){
            $this->response->result =  $this->db->lastInsertId();
            return $this->response->SetResponse(true,Language::_f("add script server"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error add script server"));
        }
    }

    public function deleteServerScripts($id_server)
    {   
        $st = $this->db->prepare("DELETE FROM servers_scripts WHERE id_server = :id_server");

        $st->bindParam(':id_server',$id_server);
        
        $this->response->result = null;
        if($st->execute()){
            $this->response->result =  $st->rowCount();
            return $this->response->SetResponse(true,Language::_f("delete script server"));
        }else{
            return $this->response->SetResponse(false,Language::_f("delete add script server"));
        }
    }


    public function updateServer($id_user,$id_server,$name,$ip_domain)
    {
        
        $st = $this->db->prepare("UPDATE servers SET ip_domain=:ip_domain,name=:name WHERE id=:id_server AND id_user=:id_user");

        $st->bindParam(':id_server',$id_server);
        $st->bindParam(':ip_domain',$ip_domain);
        $st->bindParam(':name',$name);
        $st->bindParam(':id_user',$id_user);



        $this->response->result = null;
        if($st->execute()){
            $this->response->result =  $st->rowCount();
            return $this->response->SetResponse(true,Language::_f("update server"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error update server"));
        }
    }


    public function deleteServer($id_user,$id_server)
    {
        
        $st = $this->db->prepare("UPDATE servers SET status=0 WHERE id=:id_server AND id_user=:id_user");

        $st->bindParam(':id_server',$id_server);
        $st->bindParam(':id_user',$id_user);



        $this->response->result = null;
        if($st->execute()){
            $this->response->result =  $st->rowCount();
            return $this->response->SetResponse(true,Language::_f("deleted server"));
        }else{
            return $this->response->SetResponse(false,Language::_f("error deleted server"));
        }
    }




}