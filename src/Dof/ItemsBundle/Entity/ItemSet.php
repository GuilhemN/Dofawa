<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\ExportableInterface;
use XN\DataBundle\ImportableTrait;
use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;

use XN\DataBundle\LocalizedNameTrait;
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
            'items' => array_map(function ($ent) { return $ent->exportData(false); }, $this->items->toArray()),
            'release' => $this->release,
            'preliminary' => $this->preliminary,
            'deprecated' => $this->deprecated
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        return false;
    }
}
