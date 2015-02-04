<?php 
namespace Dof\Bundle\User\CharacterBundle;

class UtilityBuild
{
	public function calcCH($criticalHits, $characteristics){
        $denominator = $criticalHits - $characteristics->getCriticalHits();
        return  floor($denominator* M_E*1.1 / Log($characteristics->getAgility() +12));
    }

    public function averageWithCH($min, $max, $criticalHits=0){
    	if ($criticalHits == 0) {
    		return ($min + $max)/2;
    	}

    	$coef = 1 / $criticalHits;
    	return ($min * (1 - $coef) + $max * $coef);
    }
}