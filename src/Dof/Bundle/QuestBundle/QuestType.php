<?php

namespace Dof\Bundle\QuestBundle;

use XN\Common\Enum;

class QuestType extends Enum
{
    const ONE_SHOT = 0;
    const REPEATABLE = 1;
    const ALMANAX = 2;

    private function __construct() { }

    public static function isValid($itemSlot)
    {
        return $itemSlot >= 0 & $itemSlot <= 2;
    }
}
