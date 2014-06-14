<?php

namespace Dof\MapBundle;

class SubAreaType
{
	const NORMAL = 0;
	const NEUTRAL = 1;
	const CONQUEST_VILLAGE = 2;

	private function __construct() { }

	public static function isValid($type)
	{
		return $type >= 0 && $type <= 2;
	}
}