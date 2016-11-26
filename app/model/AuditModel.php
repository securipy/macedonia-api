<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Auth;

class AuditModel
{
    private $db;
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }


    public function setAudit($id_user,$name)
    {
        $st = $this->db->prepare("INSERT INTO audit (id_user,name) VALUES (:id_user,:name)");

        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':name',$name);

        $this->response->result = null;
        if($st->execute()){
            $this->response->result =  $this->db->lastInsertId();
            return $this->response->SetResponse(true,"New audit created");
        }else{
            return $this->response->SetResponse(false,"Error creating audit");
        }
    }


    public function getAuditById($id,$id_user)
    {

        $sql = "SELECT * FROM audit WHERE id_user=:id_user AND id=:id";

        $st = $this->db->prepare($sql);

        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':id',$id);

        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,"Audit data");
        }else{
            return $this->response->SetResponse(false,"Error get audit");
        }
    }


    public function getAuditsByUserId($id_user)
    {
        $sql = "SELECT * FROM audit WHERE id_user=:id_user";
        $st = $this->db->prepare($sql);

        $st->bindParam(':id_user',$id_user);

        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false);
        }


    }


}