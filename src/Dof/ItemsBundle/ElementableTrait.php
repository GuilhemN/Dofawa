<?php

namespace Dof\ItemsBundle;

use Doctrine\ORM\Mapping as ORM;

trait ElementableTrait
{
    /**
     * @ORM\Column(name="elements", type="json_array")
     */
    protected $elements;

    public function __construct(){
        $this->elements = array();
    }



    public function setElements(array $elements)
    {
        $this->elements = $elements;
        return $this;
    }
    public function addElement($element)
    {
        $this->elements[] = $element;
        return $this;
    }
    public function removeElement($element)
    {
        $key = array_search($element, $this->elements);
        if ($key !== false)
            array_splice($this->elements, $key, 1);
        return $this;
    }
    public function getElements()
    {
        return $this->elements;
    }

    public function getElementsMetadata(){
        return array(
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
    }

    public function updateElements()
    {
        $elements = array('earth', 'fire', 'water', 'air');
        $metadata = $this->getElementsMetadata();

        $caracts = $this->getCharacteristicsForElements($metadata);

        $biggestCaract = null;

        foreach($elements as $element)
            if($biggestCaract === null or $caracts[$element] > $biggestCaract)
                $biggestCaract = $caracts[$element];

        if($biggestCaract < 0)
            $biggestCaract = 0;

        $itemElements = array();

        foreach($elements as $element)
            if($caracts[$element] > 0 && !empty($biggestCaract) && ($caracts[$element] * 100 / $biggestCaract) > 56 )
                $itemElements[] = $element;

        $this->elements = $itemElements;
    }

    public function getParentElements(){
        return null;
    }

	/**
	 * Get the main characts
	 *
	 * @return array
	 */
	public abstract function getCharacteristicsForElements($metadata, array $caracts = array());
}
