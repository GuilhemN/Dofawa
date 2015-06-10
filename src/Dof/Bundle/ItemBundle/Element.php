<?php

namespace Dof\Bundle\ItemBundle;

use XN\Common\Enum;

class Element extends Enum
{
    const NEUTRAL = 0;
    const EARTH = 1;
    const FIRE = 2;
    const WATER = 3;
    const AIR = 4;
    const HEAL = 5;
    const AP_LOSS = 6;

    private function __construct()
    {
    }

    public static function isValid($element)
    {
        return $element >= 0 & $element <= 6;
    }
}
