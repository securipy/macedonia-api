<?php
namespace App\Validation;

use App\Lib\Response;

class authValidation {

    public static function Validate($data){
        $response = new Response();
        
        
        $key = 'password';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'email';
        if($result_email = self::checkEmail($data[$key])){
            $response->errors[$key][] = $result_email;    
        }
        
        $response->setResponse(count($response->errors) === 0);


        return $response;
    }


    public static function checkEmail($email){
        if(empty($email)) {
            return 'Este campo es obligatorio';
        } else {
            $value = $email;

            if( !filter_var($value, FILTER_VALIDATE_EMAIL) ) {
               return 'Valor ingresado no es un correo válido';
           }
       }
       return "";
   }

}