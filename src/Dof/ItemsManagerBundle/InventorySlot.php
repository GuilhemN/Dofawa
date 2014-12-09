<?php

namespace Dof\ItemsManagerBundle;

use XN\Common\Enum;

class InventorySlot extends Enum
{
	const AMULET = 1;
	const WEAPON = 2;
	const RING = 3;
	const BELT = 4;
	const BOOTS = 5;
	const SHIELD = 7;
	const HAT = 10;
	const CLOAK = 11;
	const PET = 12;
	const DOFUS = 13;
	const MOUNT = 21;

	private function __construct() { }

	public static function isValid($inventorySlot)
	{
		return $inventorySlot >= 1 & $inventorySlot <= 23;
	}
}