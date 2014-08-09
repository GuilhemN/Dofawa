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

use Dof\ItemsBundle\ItemTemplateFactory;

/**
 * ItemType
 *
 * @ORM\Table(name="dof_item_types")
 * @ORM\Entity(repositoryClass="ItemTypeRepository")
 */
class ItemType implements IdentifiableInterface, TimestampableInterface, SluggableInterface, ExportableInterface
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
     * @var integer
     *
     * @ORM\Column(name="slot", type="integer")
     */
    private $slot;

    /**
     * @var string
     *
     * @ORM\Column(name="effect_area", type="string", length=31, nullable=true)
     */
    private $effectArea;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemTemplate", mappedBy="type")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return ItemType
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
     * Set slot
     *
     * @param integer $slot
     * @return ItemType
     */
    public function setSlot($slot)
    {
        $this->slot = $slot;

        return $this;
    }

    /**
     * Get slot
     *
     * @return integer
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Set effectArea
     *
     * @param string $effectArea
     * @return ItemType
     */
    public function setEffectArea($effectArea)
    {
        $this->effectArea = $effectArea;

        return $this;
    }

    /**
     * Get effectArea
     *
     * @return string
     */
    public function getEffectArea()
    {
        return $this->effectArea;
    }

    /**
     * Add items
     *
     * @param ItemTemplate $items
     * @return ItemType
     */
    public function addItem(ItemTemplate $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param ItemTemplate $items
     * @return ItemType
     */
    public function removeItem(ItemTemplate $items)
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

    public function createItemTemplate($autoAdd = true)
    {
        $item = ItemTemplateFactory::createItemTemplate($this->getSlot(), $this->getId());
        $item->setType($this);
        if ($autoAdd)
            $this->addItem($item);
        return $item;
    }

    public function __toString()
    {
        return $this->nameFr;
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return $this->exportTimestampableData($full) + $this->exportSluggableData($full) + [
            'name' => $this->getName($locale),
            'slot' => $this->slot
        ] + ($full ? [
            'effectArea' => $this->effectArea,
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
