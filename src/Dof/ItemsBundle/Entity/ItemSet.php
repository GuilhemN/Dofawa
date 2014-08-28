<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

use XN\Rest\ExportableInterface;
use XN\Rest\ImportableTrait;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * ItemSet
 *
 * @ORM\Table(name="dof_item_sets")
 * @ORM\Entity(repositoryClass="ItemSetRepository")
 */
class ItemSet implements IdentifiableInterface, TimestampableInterface, SluggableInterface, ExportableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ImportableTrait, ReleaseBoundTrait, LocalizedNameTrait;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="EquipmentTemplate", mappedBy="set")
     */
    private $items;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemSetCombination", mappedBy="set")
     * @ORM\OrderBy({ "itemCount" = "ASC", "id" = "ASC" })
     */
    private $combinations;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->combinations = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return ItemSet
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add items
     *
     * @param EquipmentTemplate $items
     * @return ItemSet
     */
    public function addItem(EquipmentTemplate $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param EquipmentTemplate $items
     * @return ItemSet
     */
    public function removeItem(EquipmentTemplate $items)
    {
        $this->items->removeElement($items);

        return $this;
    }

    /**
     * Get items
     *
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add combinations
     *
     * @param ItemSetCombination $combinations
     * @return ItemSet
     */
    public function addCombination(ItemSetCombination $combinations)
    {
        $this->combinations[] = $combinations;

        return $this;
    }

    /**
     * Remove combinations
     *
     * @param ItemSetCombination $combinations
     * @return ItemSet
     */
    public function removeCombination(ItemSetCombination $combinations)
    {
        $this->combinations->removeElement($combinations);

        return $this;
    }

    /**
     * Get combinations
     *
     * @return Collection
     */
    public function getCombinations()
    {
        return $this->combinations;
    }

    public function __toString()
    {
        return $this->nameFr;
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return $this->exportTimestampableData($full) + $this->exportSluggableData($full) + [
            'name' => $this->getName($locale)
        ] + ($full ? [
            'items' => array_map(function ($ent) use ($locale) { return $ent->exportData(false, $locale); }, $this->items->toArray()),
            'release' => $this->release,
            'preliminary' => $this->preliminary,
            'deprecated' => $this->deprecated
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        return false;
    }

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

        foreach($caracts as &$v)
            $v -= $smallestCaract;

        $itemElements = array();

        foreach($elements as $element)
            if($caracts[$element] > 0 && !empty($biggestCaract) && ($caracts[$element] * 100 / $biggestCaract) > 56 )
                $itemElements[] = $element;

        return $itemElements;
    }

    public function getCharacteristicsForElements($metadata, array $caracts = array()){

        $biggestCombination = null;
        foreach($this->getCombinations() as $combination)
            if($biggestCombination === null or $biggestCombination->getItemCount() < $combination->getItemCount())
                $biggestCombination = $combination;

        foreach($metadata as $k => $v){
            $caracts[$v['element']] += $biggestCombination->{'get' . ucfirst($k)}() * $v['weight'];
        }

        foreach($this->getItems() as $item)
            $caracts = $item->getCharacteristicsForElements($metadata, $caracts);

        return $caracts;
    }
}
