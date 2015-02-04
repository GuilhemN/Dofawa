<?php

namespace Dof\Bundle\MapBundle;

use XN\Common\Enum;

class MapType extends Enum
{
    const OUTDOOR = 0;
    const INDOOR = 1;

    private function __construct() { }

    public static function isValid($element)
    {
        return $element >= 0 & $element <= 1;
    }
}
