<?php

namespace Dof\ItemsBundle;

trait ElementableTrait
{


    public function getElements()
    {
        $elements = array('earth', 'fire', 'water', 'air');
        $metadata = array(
            'strength' => array('element' => 'earth', 'weight' => 1),
            'intelligence' => array('element' => 'fire', 'weight' => 1),
            'chance' => array('element' => 'water', 'weight' => 1),
            'agility' => array('element' => 'air', 'weight' => 1),
            'neutralDamage' => array('element' => 'earth', 'weight' => 2.5),
            'earthDamage' => array('element' => 'earth', 'weight' => 2.5),
            'fireDamage' => array('element' => 'fire', 'weight' => 5),
            'waterDamage' => array('element' => 'water', 'weight' => 5),
            'airDamage' => array('element' => 'air', 'weight' => 5)
        );

        $caracts = $this->getCharacteristicsForElements($metadata);

        $smallestCaract = null;
        $biggestCaract = null;

        foreach($elements as $element){
            if($smallestCaract === null or $caracts[$element] < $smallestCaract)
                $smallestCaract = $caracts[$element];

            if($biggestCaract === null or $caracts[$element] > $biggestCaract)
                $biggestCaract = $caracts[$element];
        }

        if($smallestCaract < 0)
            $smallestCaract = 0;
        if($biggestCaract < 0)
            $biggestCaract = 0;

        $itemElements = array();

        foreach($elements as $element)
            if($caracts[$element] > 0 && !empty($biggestCaract) && ($caracts[$element] * 100 / $biggestCaract) > 56 )
                $itemElements[] = $element;

        return $itemElements;
    }

	/**
	 * Get the main characts
	 *
	 * @return array
	 */
	public abstract function getCharacteristicsForElements($metadata, array $caracts = array());
}
