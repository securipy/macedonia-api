<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Language,
App\Lib\Auth;

class AwsModel
{
    private $db;
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }


    public function getCredentials($id_user)
    {
        $sql = "SELECT * FROM aws_credentials WHERE id_user=:id_user";
        $st = $this->db->prepare($sql);
        $st->bindParam(':id_user',$id_user);

        $this->response->result = null;
        if($st->execute()){
            $this->response->result = $st->fetchAll();
            return $this->response->SetResponse(true,Language::_f('aws credentials'));
        }else{
            return $this->response->SetResponse(false,Language::_f('error get aws credentials'));
        }
    }


}