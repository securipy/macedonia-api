<?php
namespace App\Validation;

use App\Lib\Response;

class auditValidation {

    public static function Validate($data) {
        $response = new Response();
        
        
        $key = 'name';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];
        }

        $response->setResponse(count($response->errors) === 0);


        return $response;
    }





}