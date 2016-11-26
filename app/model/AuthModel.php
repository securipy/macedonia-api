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

}