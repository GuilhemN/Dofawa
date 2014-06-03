<?php

namespace Dof\ItemsBundle;

class Element
{
	const NEUTRAL = 0;
	const EARTH = 1;
	const FIRE = 2;
	const WATER = 3;
	const AIR = 4;
	const HEAL = 5;

	private function __construct() { }

	public static function isValid($element)
	{
		return $element >= 0 && $element <= 5;
	}
}