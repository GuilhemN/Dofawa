<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;

use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * ItemType
 *
 * @ORM\Table(name="dof_item_types")
 * @ORM\Entity(repositoryClass="ItemTypeRepository")
 */
class ItemType implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ReleaseBoundTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="slot", type="integer")
     */
    private $slot;

    /**
     * @var string
     *
     * @ORM\Column(name="effect_area", type="string", length=31)
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ItemType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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

    public function __toString()
    {
        return $this->name;
    }
}
