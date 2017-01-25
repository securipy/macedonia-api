<?php
namespace App\Validation;

use App\Lib\Response,
    App\Validation\masterValidation;


class devicesValidation {

    public static function Validate($data){
        $response = new Response();
        
        $key = 'ip_domain';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->setResponse(count($response->errors) === 0);


        return $response;
    }


}