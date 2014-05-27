<?php
namespace XN\UtilityBundle;

class This
{
	private function __construct() { }
	
	public static function __callStatic($name, $arguments)
	{
		$that = array_shift($arguments);
		return call_user_func_array([ $that, $name ], $arguments);
	}
}