<?php
namespace App\Validation;

use App\Lib\Response;

class XRequestValidation {

	public static function Validate($data,$action = 'new') {
		$response = new Response();


		$key = 'domain';
		if(empty($data[$key])) {
			$response->errors[$key][] = 'Este campo es obligatorio';
		} else {
			

			if(!checkdnsrr($data[$key], 'A')){
				$response->errors[$key][] = 'Dominio no valido';
			}else{
				$value = $data[$key];
			}
		}
		
		if($action == 'new' or $action == 'update'){
			$key = 'max_request';
			if(empty($data[$key])) {
				$response->errors[$key][] = 'Este campo es obligatorio';
			} else {
				$value = $data[$key];

				if(!filter_var($data[$key],  FILTER_VALIDATE_INT)){
					$response->errors[$key][] = 'Numero de peticiones no valido';
				}
			}
		}

		if($action == 'new' or $action == 'update'){
			$key = 'type';
			if(empty($data[$key])) {
				$response->errors[$key][] = 'Este campo es obligatorio';
			} else {
				$value = $data[$key];

				if(!('1' <= $data[$key]) && !($data[$key] <= '3')){
					$response->errors[$key][] = 'Tipo de limite de request no valido';
				}
			}
		}

		if($action == 'delete' or $action == 'update'){
			$key = 'id';
			if(empty($data[$key])) {
				$response->errors[$key][] = 'Este campo es obligatorio';
			} else {
				$value = $data[$key];

				if(!filter_var($data[$key],  FILTER_VALIDATE_INT)){
					$response->errors[$key][] = 'Numero de peticiones no valido';
				}
			}
		}


		$response->setResponse(count($response->errors) === 0);


		return $response;


	}
}

?>