<?php

namespace Dof\BuildBundle;

use XN\Common\Enum;
use Dof\ItemsBundle\ItemSlot;

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

	public static function getRelations(){
		return [
			self::ANIMAL => [
				ItemSlot::PET,
				ItemSlot::MOUNT
			],
			self::DOFUS1 => ItemSlot::DOFUS,
			self::DOFUS2 => ItemSlot::DOFUS,
			self::DOFUS3 => ItemSlot::DOFUS,
			self::DOFUS4 => ItemSlot::DOFUS,
			self::DOFUS5 => ItemSlot::DOFUS,
			self::DOFUS6 => ItemSlot::DOFUS,
			self::HAT => ItemSlot::HAT,
			self::CLOAK => ItemSlot::CLOAK,
			self::AMULET => ItemSlot::AMULET,
			self::WEAPON => ItemSlot::WEAPON,
			self::RING1 => ItemSlot::RING,
			self::RING2 => ItemSlot::RING,
			self::BELT => ItemSlot::BELT,
			self::BOOTS => ItemSlot::BOOTS,
			self::SHIELD => ItemSlot::SHIELD,
		];
	}

	public static function getItemsSlot($buildSlot){
		if(!self::isValid($buildSlot))
			return;

		return self::getRelations()[$buildSlot];
	}

	public static function getInversedRelations() {
		$return = array();
		foreach(self::getRelations() as $k => $v){
			if(is_array($v))
				foreach($v as $v2)
					$return[$v2] = $k;
			else
				$return[$v] = $k;
		}

		return $return;
	}

	public static function getBuildSlot($itemSlot){
		if(!ItemSlot::isValid($buildSlot))
			return;

		return self::getInversedRelations()[$itemSlot];
	}
}
