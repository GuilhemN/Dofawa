<?php

namespace XN\Common;

abstract class Enum
{
    private function __construct() { }

    public static function isValid($value)
    {
        return static::getName($value) !== null;
    }

    public static function getValue($name)
    {
        $values = static::getValues();
        if (!isset($values[$name]))
            return null;
        return $values[$name];
    }

    public static function getName($value)
    {
        $name = array_search($value, static::getValues());
        if ($name === false)
            return null;
        return $name;
    }

    public static function getValues($case_lower = false)
    {
        static $values = null;
        if ($values === null) {
            $clazz = new \ReflectionClass(get_called_class());
            $values = $clazz->getConstants();
        }

        if($case_lower)
            $values = array_change_key_case($values);

        return $values;
    }
    public static function getPrefixedNames($prefix, $case_lower = true){
        return array_map(function ($v) use ($prefix) { return $prefix . $v; }, self::getValues($case_lower));
    }
}
