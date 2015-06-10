<?php

// This code is automatically generated by a tool

// You can regenerate it using the command :
// php app/console generate:dof:characteristic

// The template is at :
// src/Dof/GeneratorBundle/Resources/views/ItemBundle/CharacteristicsMetadata.php.twig


namespace Dof\Bundle\ItemBundle;

class CharacteristicsMetadata
{
    private static $data = null;

    public static function getAll()
    {
        if (self::$data === null) {
            self::$data = [
                'vitality' => [
                    'id' => 11,
                    'name' => 'vitality',
                    'positiveEffectId' => 125,
                    'negativeEffectId' => 153,
                ],
                'strength' => [
                    'id' => 10,
                    'name' => 'strength',
                    'positiveEffectId' => 118,
                    'negativeEffectId' => 157,
                ],
                'intelligence' => [
                    'id' => 15,
                    'name' => 'intelligence',
                    'positiveEffectId' => 126,
                    'negativeEffectId' => 155,
                ],
                'chance' => [
                    'id' => 13,
                    'name' => 'chance',
                    'positiveEffectId' => 123,
                    'negativeEffectId' => 152,
                ],
                'agility' => [
                    'id' => 14,
                    'name' => 'agility',
                    'positiveEffectId' => 119,
                    'negativeEffectId' => 154,
                ],
                'wisdom' => [
                    'id' => 12,
                    'name' => 'wisdom',
                    'positiveEffectId' => 124,
                    'negativeEffectId' => 156,
                ],
                'power' => [
                    'id' => 17,
                    'name' => 'power',
                    'positiveEffectId' => 138,
                    'negativeEffectId' => null,
                ],
                'criticalHits' => [
                    'id' => 18,
                    'name' => 'criticalHits',
                    'positiveEffectId' => 115,
                    'negativeEffectId' => 171,
                ],
                'ap' => [
                    'id' => 1,
                    'name' => 'ap',
                    'positiveEffectId' => 111,
                    'negativeEffectId' => 168,
                ],
                'mp' => [
                    'id' => 23,
                    'name' => 'mp',
                    'positiveEffectId' => 128,
                    'negativeEffectId' => 169,
                ],
                'range' => [
                    'id' => 19,
                    'name' => 'range',
                    'positiveEffectId' => 117,
                    'negativeEffectId' => 116,
                ],
                'summons' => [
                    'id' => 26,
                    'name' => 'summons',
                    'positiveEffectId' => 182,
                    'negativeEffectId' => null,
                ],
                'damage' => [
                    'id' => 16,
                    'name' => 'damage',
                    'positiveEffectId' => 112,
                    'negativeEffectId' => null,
                ],
                'neutralDamage' => [
                    'id' => 92,
                    'name' => 'neutralDamage',
                    'positiveEffectId' => 430,
                    'negativeEffectId' => 431,
                ],
                'earthDamage' => [
                    'id' => 88,
                    'name' => 'earthDamage',
                    'positiveEffectId' => 422,
                    'negativeEffectId' => 423,
                ],
                'fireDamage' => [
                    'id' => 89,
                    'name' => 'fireDamage',
                    'positiveEffectId' => 424,
                    'negativeEffectId' => 425,
                ],
                'waterDamage' => [
                    'id' => 90,
                    'name' => 'waterDamage',
                    'positiveEffectId' => 426,
                    'negativeEffectId' => 427,
                ],
                'airDamage' => [
                    'id' => 91,
                    'name' => 'airDamage',
                    'positiveEffectId' => 428,
                    'negativeEffectId' => 429,
                ],
                'heals' => [
                    'id' => 49,
                    'name' => 'heals',
                    'positiveEffectId' => 178,
                    'negativeEffectId' => 179,
                ],
                'prospecting' => [
                    'id' => 48,
                    'name' => 'prospecting',
                    'positiveEffectId' => 176,
                    'negativeEffectId' => 177,
                ],
                'initiative' => [
                    'id' => 44,
                    'name' => 'initiative',
                    'positiveEffectId' => 174,
                    'negativeEffectId' => 175,
                ],
                'reflectedDamage' => [
                    'id' => 50,
                    'name' => 'reflectedDamage',
                    'positiveEffectId' => 220,
                    'negativeEffectId' => null,
                ],
                'percentNeutralResistance' => [
                    'id' => 37,
                    'name' => 'percentNeutralResistance',
                    'positiveEffectId' => 214,
                    'negativeEffectId' => 219,
                ],
                'percentEarthResistance' => [
                    'id' => 33,
                    'name' => 'percentEarthResistance',
                    'positiveEffectId' => 210,
                    'negativeEffectId' => 215,
                ],
                'percentFireResistance' => [
                    'id' => 34,
                    'name' => 'percentFireResistance',
                    'positiveEffectId' => 213,
                    'negativeEffectId' => 218,
                ],
                'percentWaterResistance' => [
                    'id' => 35,
                    'name' => 'percentWaterResistance',
                    'positiveEffectId' => 211,
                    'negativeEffectId' => 216,
                ],
                'percentAirResistance' => [
                    'id' => 36,
                    'name' => 'percentAirResistance',
                    'positiveEffectId' => 212,
                    'negativeEffectId' => 217,
                ],
                'neutralResistance' => [
                    'id' => 58,
                    'name' => 'neutralResistance',
                    'positiveEffectId' => 244,
                    'negativeEffectId' => null,
                ],
                'earthResistance' => [
                    'id' => 54,
                    'name' => 'earthResistance',
                    'positiveEffectId' => 240,
                    'negativeEffectId' => null,
                ],
                'fireResistance' => [
                    'id' => 55,
                    'name' => 'fireResistance',
                    'positiveEffectId' => 243,
                    'negativeEffectId' => null,
                ],
                'waterResistance' => [
                    'id' => 56,
                    'name' => 'waterResistance',
                    'positiveEffectId' => 241,
                    'negativeEffectId' => null,
                ],
                'airResistance' => [
                    'id' => 57,
                    'name' => 'airResistance',
                    'positiveEffectId' => 242,
                    'negativeEffectId' => null,
                ],
                'percentNeutralResistanceInPvp' => [
                    'id' => 63,
                    'name' => 'percentNeutralResistanceInPvp',
                    'positiveEffectId' => 254,
                    'negativeEffectId' => null,
                ],
                'percentEarthResistanceInPvp' => [
                    'id' => 59,
                    'name' => 'percentEarthResistanceInPvp',
                    'positiveEffectId' => 250,
                    'negativeEffectId' => null,
                ],
                'percentFireResistanceInPvp' => [
                    'id' => 60,
                    'name' => 'percentFireResistanceInPvp',
                    'positiveEffectId' => 253,
                    'negativeEffectId' => null,
                ],
                'percentWaterResistanceInPvp' => [
                    'id' => 61,
                    'name' => 'percentWaterResistanceInPvp',
                    'positiveEffectId' => 251,
                    'negativeEffectId' => null,
                ],
                'percentAirResistanceInPvp' => [
                    'id' => 62,
                    'name' => 'percentAirResistanceInPvp',
                    'positiveEffectId' => 252,
                    'negativeEffectId' => null,
                ],
                'neutralResistanceInPvp' => [
                    'id' => 68,
                    'name' => 'neutralResistanceInPvp',
                    'positiveEffectId' => 264,
                    'negativeEffectId' => null,
                ],
                'earthResistanceInPvp' => [
                    'id' => 64,
                    'name' => 'earthResistanceInPvp',
                    'positiveEffectId' => 260,
                    'negativeEffectId' => null,
                ],
                'fireResistanceInPvp' => [
                    'id' => 65,
                    'name' => 'fireResistanceInPvp',
                    'positiveEffectId' => 263,
                    'negativeEffectId' => null,
                ],
                'waterResistanceInPvp' => [
                    'id' => 66,
                    'name' => 'waterResistanceInPvp',
                    'positiveEffectId' => 261,
                    'negativeEffectId' => null,
                ],
                'airResistanceInPvp' => [
                    'id' => 67,
                    'name' => 'airResistanceInPvp',
                    'positiveEffectId' => 262,
                    'negativeEffectId' => null,
                ],
                'lock' => [
                    'id' => 79,
                    'name' => 'lock',
                    'positiveEffectId' => 753,
                    'negativeEffectId' => 755,
                ],
                'dodge' => [
                    'id' => 78,
                    'name' => 'dodge',
                    'positiveEffectId' => 752,
                    'negativeEffectId' => 754,
                ],
                'apReduction' => [
                    'id' => 82,
                    'name' => 'apReduction',
                    'positiveEffectId' => 410,
                    'negativeEffectId' => 411,
                ],
                'mpReduction' => [
                    'id' => 83,
                    'name' => 'mpReduction',
                    'positiveEffectId' => 412,
                    'negativeEffectId' => 413,
                ],
                'apLossResistance' => [
                    'id' => 27,
                    'name' => 'apLossResistance',
                    'positiveEffectId' => 160,
                    'negativeEffectId' => 162,
                ],
                'mpLossResistance' => [
                    'id' => 28,
                    'name' => 'mpLossResistance',
                    'positiveEffectId' => 161,
                    'negativeEffectId' => 163,
                ],
                'criticalDamage' => [
                    'id' => 86,
                    'name' => 'criticalDamage',
                    'positiveEffectId' => 418,
                    'negativeEffectId' => 419,
                ],
                'criticalResistance' => [
                    'id' => 87,
                    'name' => 'criticalResistance',
                    'positiveEffectId' => 420,
                    'negativeEffectId' => 421,
                ],
                'pushbackDamage' => [
                    'id' => 84,
                    'name' => 'pushbackDamage',
                    'positiveEffectId' => 414,
                    'negativeEffectId' => 415,
                ],
                'pushbackResistance' => [
                    'id' => 85,
                    'name' => 'pushbackResistance',
                    'positiveEffectId' => 416,
                    'negativeEffectId' => 417,
                ],
                'trapPower' => [
                    'id' => 69,
                    'name' => 'trapPower',
                    'positiveEffectId' => 226,
                    'negativeEffectId' => null,
                ],
                'trapDamage' => [
                    'id' => 70,
                    'name' => 'trapDamage',
                    'positiveEffectId' => 225,
                    'negativeEffectId' => null,
                ],
            ];
        }

        return self::$data;
    }

    public static function getByName($name)
    {
        $all = self::getAll();

        return $all[$name];
    }
}
