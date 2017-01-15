<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Language,
App\Lib\Auth;

class UserModel
{
  private $db;
  private $response;

  public function __construct($db)
  {
    $this->db = $db;
    $this->response = new Response();
  }
  /**
   * [getFromEmail Check if email exist]
   * @param  [string] $email [email to check if exist in database]
   * @return [bolean]        [return 1 or 0]
   */
  public function getFromEmail($email)
  {

    $st = $this->db->prepare("SELECT COUNT(*) AS count_user FROM users WHERE email=:email");

    $st->bindParam(':email',$email);

    if($st->execute()){
      $result = $st->fetch();
      $this->response->result = $result;
      return $this->response->SetResponse(true);
    }else{
      return $this->response->SetResponse(false,Language::_f('error checking email'));
    }
  }


    /**
     * [register insert data of user]
     * @param  [type] $name     [Name user]
     * @param  [type] $email    [Email user]
     * @param  [type] $password [password user sha1]
     * @param  [type] $salt     [salt user random]
     * @return [response]       [true or false]
     */
    public function register($name,$email,$password,$salt)
    {
      $st = $this->db->prepare("INSERT INTO users (name,email,password,salt) VALUES (:name,:email,:password,:salt)");

      $st->bindParam(':name',$name);
      $st->bindParam(':email',$email);
      $st->bindParam(':password',$password);
      $st->bindParam(':salt',$salt);
      $this->response->result = null;
      if($st->execute()){
        return $this->response->SetResponse(true);
      }else{
        return $this->response->SetResponse(false);
      }

    }

    /**
     * [update update name,password user]
     * @param  [string] $name     [name user]
     * @param  [string] $password [password user]
     * @param  [string] $salt     [salt user]
     * @param  [int] $id          [id user]
     * @return [type]             [description]
     */
    public function update($name,$password,$salt,$id)
    {

      if (!empty($password)) {
        $st = $this->db->prepare('UPDATE users set name=:name,password=:password,salt=:salt WHERE id=:id');
        $st->bindParam(':password',$password);
        $st->bindParam(':salt',$salt);
      }else{
        $st = $this->db->prepare('UPDATE users set name=:name WHERE id=:id');
      }
        $st->bindParam(':id',$id);
        $st->bindParam(':name',$name);
      if($st->execute()){
        return $this->response->SetResponse(true);
      }else{
        return $this->response->SetResponse(false);
      }

    }

    /**
     * [setTokenRecovery Set token to recovey password]
     * @param [string] $token [Token to control recovery]
     * @param [string] $email [Email need recovery]
     * @return [Response] [true or false]
     */
    public function setTokenRecovery($token,$email)
    {
      $st = $this->db->prepare("UPDATE users SET token_recovery=:token WHERE email=:email");

      $st->bindParam(':email',$email);
      $st->bindParam(':token',$token);

      if($st->execute()){
        return $this->response->SetResponse(true);
      }else{
        return $this->response->SetResponse(false);
      }

    }
  }