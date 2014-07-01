<?php

namespace Dof\ImpExpBundle;

use XN\UtiliyBundle\FlagsEnum;

class ImporterFlags extends FlagsEnum
{
    const DRY_RUN = 1;

    private function __construct() { }

    public static function isValid($flags)
    {
        return ($flags & ~1) == 0;
    }
}
