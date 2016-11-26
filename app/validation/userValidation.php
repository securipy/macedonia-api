<?php
namespace App\Validation;

use App\Lib\Response;

class userValidation {

    public static function Validate($data, $update = false) {
        $response = new Response();
        
        
        $key = 'name';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];
            
            if(strlen($value) < 4) {
                $response->errors[$key][] = 'Debe contener como mínimo 4 caracteres';
            }
        }
        if (!$update) {
            $key = 'email';
            if($result_email = self::checkEmail($data[$key])){
                $response->errors[$key][] = $result_email;    
            }
        }
        
        $key = 'password';
        if( !$update ){
            $result =  self::checkPassword($data['password'],$data['repassword']);
            if($result !== true){
                $response->errors[$key][] = $result;
            }           
        } else {
            if(!empty($data[$key])){
                $value = $data[$key];

                $result =  self::checkPassword($data['password'],$data['repassword']);
                if($result !== true){
                    $response->errors[$key][] = $result;
                }     
            }
        }

        $response->setResponse(count($response->errors) === 0);


        return $response;
    }

    public static function checkEmail($email)
    {
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



    public static function checkPassword($password,$repassword){
        if(strlen($password) < 6){          
            return 'La contraseña debe tener al menos 6 caracteres';
        }
        if(strlen($password) > 16){
            return 'La contraseña no puede tener más de 16 caracteres';
        }
        if (!preg_match('`[a-z]`',$password)){
            return 'La contraseña debe tener al menos una letra minúscula';
        }
        if (!preg_match('`[A-Z]`',$password)){
            return 'La contraseña debe tener al menos una letra mayúscula';
        }
        if (!preg_match('`[0-9]`',$password)){
            return 'La contraseña debe tener al menos un caracter numérico';
        }
        if($password !== $repassword){
            return 'Las contraseñas no coinciden';

        }
        return true;
    }

    public static function recovery($data)
    {

        $response = new Response();

        $key = 'email';
        if($result_email = self::checkEmail($data[$key])){
            $response->errors[$key][] = $result_email;    
        }

        $response->setResponse(count($response->errors) === 0);

        return $response;
    }

    public static function updateRecovery($data,$token)
    {

        $response = new Response();
        
        $key = 'password';
        $result =  self::checkPassword($data['password'],$data['repassword']);
        if($result !== true){
            $response->errors[$key][] = $result;
        }  
        
        $key= 'token';
        if (empty($token)) {
            $response->errors[$key][] = 'Token no puede estar en blanco';
        }

        $response->setResponse(count($response->errors) === 0);

        return $response;
    }

}