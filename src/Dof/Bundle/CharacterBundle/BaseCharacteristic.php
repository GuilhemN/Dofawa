<?php

namespace Dof\Bundle\CharacterBundle;

use XN\Common\Enum;

class BaseCharacteristic extends Enum
{
    const VITALITY = 0;
    const WISDOM = 1;
    const STRENGTH = 2;
    const INTELLIGENCE = 3;
    const CHANCE = 4;
    const AGILITY = 5;

    private function __construct() { }

    public static function isValid($characteristic)
    {
        return $characteristic >= 0 & $characteristic <= 5;
    }
}
