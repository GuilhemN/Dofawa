<?php

namespace Dof\ItemsBundle;

use XN\Common\Enum;

class AnimalColorizationType extends Enum
{
	const NONE = 0;
	const COLORS = 1;
	const CHAMELEON = 2;
	const SHIFTED_CHAMELEON = 3;

	private function __construct() { }

	public static function isValid($type)
	{
		return $type >= 0 & $type <= 3;
	}
}
