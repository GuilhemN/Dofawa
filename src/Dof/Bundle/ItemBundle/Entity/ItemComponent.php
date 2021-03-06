<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;

/**
 * ItemComponent.
 *
 * @ORM\Table(name="dof_item_components")
 * @ORM\Entity(repositoryClass="ItemComponentRepository")
 */
class ItemComponent implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ItemTemplate
     *
     * @ORM\ManyToOne(targetEntity="ItemTemplate", inversedBy="components")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $compound;

    /**
     * @var ItemTemplate
     *
     * @ORM\ManyToOne(targetEntity="ItemTemplate", inversedBy="compounds")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $component;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var bool
     *
     * @ORM\Column(name="sticky", type="boolean")
     */
    private $sticky;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set compound.
     *
     * @param ItemTemplate $compound
     *
     * @return ItemComponent
     */
    public function setCompound(ItemTemplate $compound)
    {
        $this->compound = $compound;

        return $this;
    }

    /**
     * Get compound.
     *
     * @return ItemTemplate
     */
    public function getCompound()
    {
        return $this->compound;
    }

    /**
     * Set component.
     *
     * @param ItemTemplate $component
     *
     * @return ItemComponent
     */
    public function setComponent(ItemTemplate $component)
    {
        $this->component = $component;

        return $this;
    }

    /**
     * Get component.
     *
     * @return ItemTemplate
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return ItemIngredient
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    /**
     * Set sticky.
     *
     * @param bool $sticky
     *
     * @return ItemComponent
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
     * Get sticky.
     *
     * @return bool
     */
    public function getSticky()
    {
        return $this->sticky;
    }

    /**
     * Get sticky.
     *
     * @return bool
     */
    public function isSticky()
    {
        return $this->sticky;
    }
}
