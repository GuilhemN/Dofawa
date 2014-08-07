<?php

namespace Dof\ItemsBundle;

class EffectListHelper
{
    private function __construct() { }

    public static function extractCharacteristics(array &$list)
    {
        $charas = [ ];
        foreach (CharacteristicsMetadata::getAll() as $meta)
            $charas[$meta['name']] = 0;
        $emap = self::getEffectMap();
        $list2 = [ ];
        $i = 0;
        foreach ($list as $row) {
            if (isset($emap[$row['type']])) {
                $emapent = $emap[$row['type']];
                $charas[$emapent[0]] += $row['param1'] * $emapent[1];
            } else {
                $row['order'] = ++$i;
                $list2[] = $row;
            }
        }
        $list = $list2;
        return $charas;
    }

    public static function extractCharacteristicsRanges(array &$list)
    {
        $charas = [ ];
        foreach (CharacteristicsMetadata::getAll() as $meta)
            $charas[$meta['name']] = [ 'min' => 0, 'max' => 0 ];
        $emap = self::getEffectMap();
        $list2 = [ ];
        $i = 0;
        foreach ($list as $row) {
            if (isset($emap[$row['type']])) {
                $emapent = $emap[$row['type']];
                $min = $row['param1'] * $emapent[1];
                $max = (($row['param2'] == 0) ? $row['param1'] : $row['param2']) * $emapent[1];
                if ($max < $min) {
                    $tmp = $max;
                    $max = $min;
                    $min = $tmp;
                }
                $charas[$emapent[0]]['min'] += $min;
                $charas[$emapent[0]]['max'] += $max;
            } else {
                $row['order'] = ++$i;
                $list2[] = $row;
            }
        }
        $list = $list2;
        return $charas;
    }

    public static function extractWeaponDamageRows(array &$list)
    {
        $dmg = [ ];
        $list2 = [ ];
        $dmap = self::getDamageMap();
        $i = 0;
        $j = 0;
        foreach ($list as $row) {
            if (isset($dmap[$row['type']])) {
                $dmapent = $dmap[$row['type']];
                $dmg[] = [
                    'order' => ++$j,
                    'element' => $dmapent[0],
                    'min' => $row['param1'],
                    'max' => ($row['param2'] == 0) ? $row['param1'] : $row['param2'],
                    'leech' => $dmapent[1]
                ];
            } else {
                $row['order'] = ++$i;
                $list2[] = $row;
            }
        }
        $list = $list2;
        return $dmg;
    }

    private static function getEffectMap()
    {
        static $map = null;
        if ($map === null) {
            $map = [ ];
            foreach (CharacteristicsMetadata::getAll() as $meta) {
                if ($meta['positiveEffectId'] !== null)
                    $map[$meta['positiveEffectId']] = [ $meta['name'], 1 ];
                if ($meta['negativeEffectId'] !== null)
                    $map[$meta['negativeEffectId']] = [ $meta['name'], -1 ];
            }
        }
        return $map;
    }
    private static function getDamageMap()
    {
        static $map = null;
        if ($map === null)
            $map = [
                 91 => [ Element::WATER  , true  ],
                 92 => [ Element::EARTH  , true  ],
                 93 => [ Element::AIR    , true  ],
                 94 => [ Element::FIRE   , true  ],
                 95 => [ Element::NEUTRAL, true  ],
                 96 => [ Element::WATER  , false ],
                 97 => [ Element::EARTH  , false ],
                 98 => [ Element::AIR    , false ],
                 99 => [ Element::FIRE   , false ],
                100 => [ Element::NEUTRAL, false ],
                101 => [ Element::AP_LOSS, false ],
                108 => [ Element::HEAL   , false ],
                646 => [ Element::HEAL   , false ]
            ];
        return $map;
    }
}
