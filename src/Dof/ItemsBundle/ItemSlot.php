<?php

namespace Dof\ItemsBundle;

class ItemSlot
{
	const AMULET = 1;
	const WEAPON = 2;
	const RING = 3;
	const BELT = 4;
	const BOOTS = 5;
	const USEABLE = 6;
	const SHIELD = 7;
	const RESOURCE = 9;
	const HAT = 10;
	const CLOAK = 11;
	const PET = 12;
	const DOFUS = 13;
	const QUEST = 14;
	const MUTATION = 15;
	const BOOST = 16;
	const BLESSING = 17;
	const CURSE = 18;
	const RP_BUFF = 19;
	const FOLLOWER = 20;
	const MOUNT = 21;
	const LIVING_ITEM = 22;
	const SIDEKICK = 23;

	private function __construct() { }

	public static function isValid($itemSlot)
	{
		return $itemSlot >= 1 && $itemSlot <= 23;
	}
}