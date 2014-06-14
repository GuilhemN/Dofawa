<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;

/**
 * ItemComponent
 *
 * @ORM\Table(name="dof_item_components")
 * @ORM\Entity(repositoryClass="ItemComponentRepository")
 */
class ItemComponent implements IdentifiableInterface
{
    /**
     * @var integer
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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

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
     * Set compound
     *
     * @param ItemTemplate $compound
     * @return ItemComponent
     */
    public function setCompound(ItemTemplate $compound)
    {
        $this->compound = $compound;

        return $this;
    }

    /**
     * Get compound
     *
     * @return ItemTemplate
     */
    public function getCompound()
    {
        return $this->compound;
    }
    
    /**
     * Set component
     *
     * @param ItemTemplate $component
     * @return ItemComponent
     */
    public function setComponent(ItemTemplate $component)
    {
        $this->component = $component;

        return $this;
    }

    /**
     * Get component
     *
     * @return ItemTemplate
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return ItemIngredient
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
