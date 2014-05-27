<?php
namespace XN\UtilityBundle;

class LightBulb
{
	private function __construct() { }
	
	public static function change()
	{
		throw new \Exception("C'est un problème hardware !");
	}
}