<?php

namespace Dof\CharactersBundle;

class Gender
{
    const MALE = 0;
    const FEMALE = 1;

    private function __construct() { }

    public static function isValid($gender)
    {
        return $gender >= 0 && $gender <= 1;
    }
}
