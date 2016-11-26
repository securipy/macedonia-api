<?php
namespace App\Validation;

use App\Lib\Response;

class PermisionValidation {

    public static function Validate($data, $update = false) {
        $response = new Response();
        
        
        $key = 'url';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'group_user';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }


        $response->setResponse(count($response->errors) === 0);


        return $response;
    }



}