<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Auth;

class AuthModel
{
    private $db;
    private $table = 'users';
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }


    public function login($email)
    {
        $st = $this->db->prepare("SELECT * FROM users WHERE email=:email");

        $st->bindParam(':email',$email);

        if($st->execute()){
          $result = $st->fetch();
          return $result;
        }else{
          return array(false,'Error al consultar el email');
        }
    }

    public function setToken($token,$id_user)
    {
       $st = $this->db->prepare("INSERT INTO users_login (id_user,token) VALUES (:id_user,:token)");

        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':token',$token);

        if($st->execute()){
            return $this->response->SetResponse(true,'Credenciales de login guardadas');
        }else{
            return $this->response->SetResponse(false,'Error al guardar credenciales del login');
        }

    }

}