<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Auth;

class XRequestModel
{
    private $db;
    private $table = 'users';
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

   /* [getFromDomain Check if domain exist]
   * @param  [string] $domain [domain to check if exist in database]
   * @return [bolean]        [return 1 or 0]
   */
  public function getFromDomain($domain)
  {

    $st = $this->db->prepare("SELECT COUNT(*) AS count_domain FROM xrequests_urls WHERE domain=:domain");

    $st->bindParam(':domain',$domain);

    if($st->execute()){
      $result = $st->fetch();
      return $result;
    }else{
      return array(false,'Error al consultar el dominio');
    }
  }


  public function setXrequestDomain($domain,$max_request,$type)
  {

	$st = $this->db->prepare("INSERT INTO xrequests_urls (domain,max_request,type) VALUES (:domain,:max_request,:type)");

    $st->bindParam(':domain',$domain);
    $st->bindParam(':max_request',$max_request);
    $st->bindParam(':type',$type);

    if($st->execute()){
      return array(true,'Dominio activo en el sistema');
    }else{
      return array(false,'Error al consultar el email');
    }
  }


  public function UpdateXrequestDomain($domain,$max_request,$type,$id)
  {

	$st = $this->db->prepare('UPDATE xrequests_urls set max_request=:max_request,type=:type WHERE id=:id AND domain=:domain');

    $st->bindParam(':domain',$domain);
    $st->bindParam(':max_request',$max_request);
    $st->bindParam(':type',$type);
    $st->bindParam(':id',$id);

    if($st->execute()){
      return array(true,'Actualizadas las peticiones maximas');
    }else{
      return array(false,'Error al actualizar las peticiones maximas');
    }
  }


  public function DeleteXrequestDomain($domain,$id)
  {

	$st = $this->db->prepare('DELETE FROM xrequests_urls WHERE id=:id AND domain=:domain');

    $st->bindParam(':domain',$domain);
    $st->bindParam(':id',$id);

    if($st->execute()){
      return array(true,'Dominio con acceso a la api eliminado');
    }else{
      return array(false,'Error al actualizar las peticiones maximas');
    }
  }


  public function SetXrequestClientConnect($id,$url)
  {

	$st = $this->db->prepare("INSERT INTO xrequests_urls_client (id,url) VALUES (:id,:url)");

    $st->bindParam(':id',$id);
    $st->bindParam(':url',$url);

    if($st->execute()){
      return array(true,'Conexion contabilizada');
    }else{
      return array(false,'Error al actualizar las peticiones maximas');
    }
  }


}

?>