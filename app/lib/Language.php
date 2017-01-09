<?php
namespace App\Lib;

use Exception;

class Language
{

	public function _f($text,$domain = "index")
	{
		textdomain($domain);
		return _($text);
	}
}