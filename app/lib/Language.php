<?php
namespace App\Lib;

use Exception;

class Language
{

	public static function _f($text,$domain = "index")
	{
		textdomain($domain);
		return _($text);
	}
}