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
                // TODO : check if min/max reversal is needed for negative effects
                $charas[$emapent[0]]['min'] += $row['param1'] * $emapent[1];
                $charas[$emapent[0]]['max'] += $row['param2'] * $emapent[1];
            } else {
                $row['order'] = ++$i;
                $list2[] = $row;
            }
        }
        $list = $list2;
        return $charas;
    }

    private static function getEffectMap()
    {
        static $map = null;
        if ($map === null) {
            $map = [ ];
            foreach (CharacteristicsMetadata::getAll() as $meta) {
                if ($meta['positiveEffectId'] !== null)
                    $map[$meta['positiveEffectId']] = [ $meta['name'], 1, 1 ];
                if ($meta['negativeEffectId'] !== null)
                    $map[$meta['negativeEffectId']] = [ $meta['name'], -1, 2 ];
            }
        }
        return $map;
    }
}
