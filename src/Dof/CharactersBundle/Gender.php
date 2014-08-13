<?php

namespace Dof\CharactersBundle;

use XN\Common\Enum;

class Gender extends Enum
{
    const MALE = 0;
    const FEMALE = 1;

    private function __construct() { }

    public static function isValid($gender)
    {
        return $gender >= 0 & $gender <= 1;
    }
}
