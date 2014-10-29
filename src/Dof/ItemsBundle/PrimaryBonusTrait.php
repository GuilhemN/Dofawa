<?php

namespace Dof\ItemsBundle;

use Doctrine\ORM\Mapping as ORM;

trait PrimaryBonusTrait
{
    /**
     * @ORM\Column(name="primary_bonus", type="json_array", nullable=true)
     */
    protected $primaryBonus;

    public function __construct(){
        $this->primaryBonus = array();
    }

	/**
	 * Get the main characts
	 *
	 * @return array
	 */
	public abstract function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array());

    public function setPrimaryBonus(array $primaryBonus)
    {
        $this->primaryBonus = $primaryBonus;
        return $this;
    }
    public function addPrimaryBonus($primaryBonus)
    {
        $this->primaryBonus[] = $primaryBonus;
        return $this;
    }
    public function removePrimaryBonus($primaryBonus)
    {
        $key = array_search($primaryBonus, $this->primaryBonus);
        if ($key !== false)
            array_splice($this->primaryBonus, $key, 1);
        return $this;
    }
    public function getPrimaryBonus()
    {
        return $this->primaryBonus;
    }

    public function updatePrimaryBonus()
    {
        $elements = array('earth', 'fire', 'water', 'air');

        $caracts = $this->getCharacteristicsForPrimaryBonus($this->getPrimaryBonusFields());

        $biggestCaract = null;

        foreach($elements as $element){
            if(!isset($caracts[$element]))
                $caracts[$element] = 0;
            if($biggestCaract === null or $caracts[$element] > $biggestCaract)
                $biggestCaract = $caracts[$element];
        }

        if($biggestCaract < 0)
            $biggestCaract = 0;

        $itemPrimaryBonus = array();

        foreach($elements as $element)
            if($caracts[$element] > 0 && !empty($biggestCaract) && ($caracts[$element] * 100 / $biggestCaract) > 56 )
                $itemPrimaryBonus[ ] = $element;

        foreach($this->getPrimaryCharacteristics() as $field)
            if(@$caracts[$field] > 0)
                $itemPrimaryBonus[ ] = $field;

        $this->primaryBonus = $itemPrimaryBonus;
    }

    public function getCascadeForPrimaryBonus(){
        return null;
    }

    public function getPrimaryBonusFields(){
        $primaryCharacteristicsMetadatas = array();
        foreach($this->getPrimaryCharacteristics() as $field)
            $primaryCharacteristicsMetadatas[ $field ] = array('primaryBonus' => $field, 'weight' => 1);

        return array_merge($this->getElementsMetadata(), $primaryCharacteristicsMetadatas);
    }

    protected function getElementsMetadata(){
        return array(
            'strength' => array('primaryBonus' => 'earth', 'weight' => 1),
            'intelligence' => array('primaryBonus' => 'fire', 'weight' => 1),
            'chance' => array('primaryBonus' => 'water', 'weight' => 1),
            'agility' => array('primaryBonus' => 'air', 'weight' => 1),
            'neutralDamage' => array('primaryBonus' => 'earth', 'weight' => 2.5),
            'earthDamage' => array('primaryBonus' => 'earth', 'weight' => 2.5),
            'fireDamage' => array('primaryBonus' => 'fire', 'weight' => 5),
            'waterDamage' => array('primaryBonus' => 'water', 'weight' => 5),
            'airDamage' => array('primaryBonus' => 'air', 'weight' => 5)

        );
    }

    protected function getPrimaryCharacteristics(){
        return array(
            'range',
            'ap',
            'mp',
            'criticalHits',
            'summons'
        );
    }
}
