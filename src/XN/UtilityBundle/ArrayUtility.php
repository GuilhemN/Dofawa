<?php

namespace XN\UtilityBundle;

class ArrayUtility
{
    private function __construct() { }

    public static function isAssociative(array $array)
    {
        foreach ($array as $key => $value)
            if (!is_numeric($key))
                return true;
        return false;
    }
}
