<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Auth;

class DashboardModel
{
    private $db;
    private $table = 'users';
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function getAuditByIdUser($id_user)
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