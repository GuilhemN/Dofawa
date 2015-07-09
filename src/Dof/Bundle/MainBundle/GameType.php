<?php

namespace Dof\Bundle\MainBundle;

use XN\Common\Enum;

class GameType extends Enum
{
    const REGULAR = 0;
    const HEROIC = 1;
    const KOLOSSIUM = 2;
    const TOURNAMENT = 3;
    const EPIC = 4;

    private function __construct()
    {
    }

    public static function isValid($gameType)
    {
        return $gameType >= 0 & $gameType <= 4;
    }
}
