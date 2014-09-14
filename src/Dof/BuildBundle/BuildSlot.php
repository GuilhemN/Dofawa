<?php

namespace Dof\ItemsBundle;

use XN\Common\Enum;

class BuildSlot extends Enum
{
	const ANIMAL = 1;
	const DOFUS1 = 2;
	const DOFUS2 = 3;
	const DOFUS3 = 4;
	const DOFUS4 = 5;
	const DOFUS5 = 6;
	const DOFUS6 = 7;
	const HAT = 8;
	const CLOAK = 9;
	const AMULET = 10;
	const WEAPON = 11;
	const RING1 = 12;
	const RING2 = 13;
	const BELT = 14;
	const BOOTS = 15;
	const SHIELD = 16;

	private function __construct() { }

	public static function isValid($itemSlot)
	{
		return $itemSlot >= 1 & $itemSlot <= 16;
	}
}
