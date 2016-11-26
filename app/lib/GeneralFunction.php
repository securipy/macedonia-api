<?php
namespace App\Lib;

class GeneralFunction
{
	/**
	 * [createNullData Los campos que faltan en el envio los crea como null]
	 * @param  [type] $data       [Array con los datos del POST]
	 * @param  [type] $validInput [description]
	 * @return [type]             [description]
	 */
	public static function createNullData($data,$validInput)
	{
		foreach($validInput as $Input) {
			$data[$Input] = isset($data[$Input]) ? $data[$Input] : NULL;
		}
		return $data;
	}


	public static function getToken($ap)
	{
		$token = "";
		$c = $ap->getContainer();
        $app_token_name = $c->settings['app_token_name'];
        $token = $request->getHeader($app_token_name);
        if(isset($token[0])) $token = $token[0];
        return $token;
	}
	
}