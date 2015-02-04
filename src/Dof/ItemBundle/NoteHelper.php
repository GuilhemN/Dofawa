<?php
namespace Dof\ItemBundle;

class NoteHelper {
    public static function getPowerRate() {
        return [
            // Caracts
            'vitality' => 0.25,
            'strength' => 1,
            'intelligence' => 1,
            'chance' => 1,
            'agility' => 1,
            'wisdom' => 3,
            'initiative' => 0.1,
            'prospecting' => 3,
            'power' => 2,

            //Résistances
            'neutralResistance' => 2,
            'earthResistance' => 2,
            'fireResistance' => 2,
            'waterResistance' => 2,
            'airResistance' => 2,
            'percentNeutralResistance' => 6,
            'percentEarthResistance' => 6,
            'percentFireResistance' => 6,
            'percentWaterResistance' => 6,
            'percentAirResistance' => 6,
            'pushbackResistance' => 2,
            'criticalResistance' => 2,
            'apLossResistance' => 7,
            'mpLossResistance' => 7,

            // Bonus
            'trapPower' => 2,
            'trapDamage' => 15,
            'lock' => 4,
            'dodge' => 4,
            'apReduction' => 7,
            'mpReduction' => 7,
            'heals' => 20,
            'criticalHits' => 30,
            'summons' => 30,
            'reflectedDamage' => 30,
            'range' => 51,
            'mp' => 90,
            'ap' => 100,

            // Dommages
            'damage' => 20,
            'neutralDamage' => 5,
            'earthDamage' => 5,
            'fireDamage' => 5,
            'waterDamage' => 5,
            'airDamage' => 5
            ];
    }

    public static function calcPowerRate(array $characts, $range = true){
        $powerRates = self::getPowerRate();

        $pwrg = 0; // Power-Rate Global
        foreach($powerRates as $charact => $powerRate){
            $row = $characts[$charact];
            if($range)
                $pwrg += ($row['min'] + $row['max']) / 2 * $powerRate;
            else
                $pwrg += $row * $powerRate;
        }

        return $pwrg;
    }
}
