<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;

class UserController
{
    private $response;
    
    public function __construct($model)
    {
        $this->response = new Response();
        $this->model = $model;
    }


    /**
     * [register description]
     * @param  [array] $data [user,email,password]
     * @return [response]       [Status progres register, if is all correct o have some problem]
     */
    public function register($data,$mail)
    {

        //echo "dentro";
        $data['salt'] = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $data['password'] = hash('sha512', $data['password']. $data['salt']);

        $resultCheckEmail = $this->model->getFromEmail($data['email']);


       // var_dump($mail);


    /*    $mail->SetFrom('rotal@maubic.es', 'Rafael Otal');

        $mail->Subject    = "Alta nuevo usuario";


        $mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";

        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

        $body = "Gracias por darse de alta en el servicio de <a href='http://calendariodefestivos.com/'>fiestas</a>";

        $mail->MsgHTML($body);

        $address = "goldrak@gmail.com";
        $mail->AddAddress($address, "goldrak");


        if(!$mail->Send()) {
          return "Mailer Error: " . $mail->ErrorInfo;
        } else {
          return "Message sent!";
        }*/
        var_dump($resultCheckEmail);
        if($resultCheckEmail->response){
            if(!$resultCheckEmail->result->count_user > 0){
                $resultRegister = $this->model->register($data['name'],$data['email'],$data['password'],$data['salt']);
                //if($resultRegister->)
                return $resultRegister;
                //TODO: Send email issues #2
            }else{
              return $this->response->SetResponse(false, "Email ya registrado");
            }

            return $this->response->SetResponse(true);
        }else{
            return $resultCheckEmail;
        }
    }

    /**
     * [update description]
     * @param  [type] $data  [description]
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function update($data,$token)
    {

        if (!empty($data['password'])){
            $data['salt'] = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            $data['password'] = hash('sha512', $data['password']. $data['salt']);
        }


        $id= Auth::GetData($token)->id;
        
        return $this->model->update($data['name'],$data['password'],$data['salt'],$id); 
    }
    /**
     * [recovery description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function recovery($data)
    {
        //TODO: enviar email
        $token_recovery = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

        return $this->model->setTokenRecovery($token_recovery,$data['email']); 
    }
}

            