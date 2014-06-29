<?php

namespace XN\UtilityBundle;

abstract class FlagsEnum extends Enum
{
    private function __construct() { }

    public static function getValue($name)
    {
        $name = array_map('trim', explode(',', $name));
        $values = static::getValues();
        $value = 0;
        for ($name as $part) {
            if (!isset($values[$name]))
                return null;
            $value |= $values[$name];
        }
        return $value;
    }

    public static function getName($value)
    {
        $name = array_search($value, static::getValues());
        if ($name !== false)
            return $name;
        $values = static::getUniqueValues();
        $name = [ ];
        foreach ($values as $k => $v) {
            if (($value & $v) != 0) {
                $name[] = $k;
                $value &= ~$v;
            }
        }
        if ($value != 0)
            return null;
        return implode(', ', $name);
    }

    public static function getUniqueValues()
    {
        static $values = null;
        if ($values === null)
            $values = array_filter(static::getValues(), function ($value) {
                // true for single-1 values, false for 0 or multiple-1 values
                return $value != 0 && ($value & ($value - 1)) == 0;
            });
        return $values;
    }
}
