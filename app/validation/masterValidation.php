<?php

namespace App\Validation;

//use App\Lib\Response;

class masterValidation
{
		
	public static function validateDate($date, $format = 'Y-m-d H:i:s'){
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}




}




?>