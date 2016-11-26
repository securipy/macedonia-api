<?php
namespace App\Validation;

use App\Lib\Response;

class serverValidation {

    public static function Validate($data, $update = false) {
        $response = new Response();
        
        
        $key = 'name';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'ip_domain';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'day_scan';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }



        $response->setResponse(count($response->errors) === 0);


        return $response;
    }



}