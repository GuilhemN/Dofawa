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
use XN\DataBundle\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * ItemTemplate
 *
 * @ORM\Table(name="dof_item_templates")
 * @ORM\Entity(repositoryClass="ItemTemplateRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({"item" = "ItemTemplate", "equip" = "EquipmentTemplate", "skequip" = "SkinnedEquipmentTemplate", "weapon" = "WeaponTemplate", "animal" = "AnimalTemplate", "pet" = "PetTemplate", "mount" = "MountTemplate", "useable" = "UseableItemTemplate"})
 */
class ItemTemplate implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait, ReleaseBoundTrait;

    /**
     * @var ItemType
     *
     * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_relative_path", type="string", length=31, nullable=true)
     */
    private $iconRelativePath;

    /**
     * @var integer
     *
     * @ORM\Column(name="dominant_color", type="integer", nullable=true)
     */
    private $dominantColor;

    /**
     * @var string
     *
     * @ORM\Column(name="criteria", type="string", length=127, nullable=true)
     */
    private $criteria;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tradeable", type="boolean")
     */
    private $tradeable;

    /**
     * @var integer
     *
     * @ORM\Column(name="npc_price", type="integer")
     */
    private $npcPrice;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemTemplateEffect", mappedBy="item")
     */
    private $effects;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemComponent", mappedBy="compound")
     */
    private $components;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemComponent", mappedBy="component")
     */
    private $compounds;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->effects = new ArrayCollection();
        $this->components = new ArrayCollection();
        $this->compounds = new ArrayCollection();
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
     * Set type
     *
     * @param ItemType $type
     * @return ItemTemplate
     */
    public function setType(ItemType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return ItemType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set description
     *
     * @param string $description
     * @return ItemTemplate
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set iconRelativePath
     *
     * @param string $iconRelativePath
     * @return ItemTemplate
     */
    public function setIconRelativePath($iconRelativePath)
    {
        $this->iconRelativePath = $iconRelativePath;

        return $this;
    }

    /**
     * Get iconRelativePath
     *
     * @return string
     */
    public function getIconRelativePath()
    {
        return $this->iconRelativePath;
    }

    /**
     * Set dominantColor
     *
     * @param integer $dominantColor
     * @return ItemTemplate
     */
    public function setDominantColor($dominantColor)
    {
        $this->dominantColor = $dominantColor;

        return $this;
    }

    /**
     * Get dominantColor
     *
     * @return integer
     */
    public function getDominantColor()
    {
        return $this->dominantColor;
    }

    /**
     * Set criteria
     *
     * @param string $criteria
     * @return ItemTemplate
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * Get criteria
     *
     * @return string
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return ItemTemplate
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * Set weight
     *
     * @param integer $weight
     * @return ItemTemplate
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set tradeable
     *
     * @param boolean $tradeable
     * @return ItemTemplate
     */
    public function setTradeable($tradeable)
    {
        $this->tradeable = $tradeable;

        return $this;
    }

    /**
     * Get tradeable
     *
     * @return boolean
     */
    public function getTradeable()
    {
        return $this->tradeable;
    }

    /**
     * Get tradeable
     *
     * @return boolean
     */
    public function isTradeable()
    {
        return $this->tradeable;
    }

    /**
     * Set npcPrice
     *
     * @param integer $npcPrice
     * @return ItemTemplate
     */
    public function setNpcPrice($npcPrice)
    {
        $this->npcPrice = $npcPrice;

        return $this;
    }

    /**
     * Get npcPrice
     *
     * @return integer
     */
    public function getNpcPrice()
    {
        return $this->npcPrice;
    }

    /**
     * Add effects
     *
     * @param ItemTemplateEffect $effects
     * @return ItemTemplate
     */
    public function addEffect(ItemTemplateEffect $effects)
    {
        $this->effects[] = $effects;

        return $this;
    }

    /**
     * Remove effects
     *
     * @param ItemTemplateEffect $effects
     * @return ItemTemplate
     */
    public function removeEffect(ItemTemplateEffect $effects)
    {
        $this->effects->removeElement($effects);

        return $this;
    }

    /**
     * Get effects
     *
     * @return Collection
     */
    public function getEffects()
    {
        return $this->effects;
    }
    
    /**
     * Add components
     *
     * @param ItemComponent $components
     * @return ItemTemplate
     */
    public function addComponent(ItemComponent $components)
    {
        $this->components[] = $components;

        return $this;
    }

    /**
     * Remove components
     *
     * @param ItemComponent $components
     * @return ItemTemplate
     */
    public function removeComponent(ItemComponent $components)
    {
        $this->components->removeElement($components);

        return $this;
    }

    /**
     * Get components
     *
     * @return Collection
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Add compounds
     *
     * @param ItemComponent $compounds
     * @return ItemTemplate
     */
    public function addCompound(ItemComponent $compounds)
    {
        $this->compounds[] = $compounds;

        return $this;
    }

    /**
     * Remove compounds
     *
     * @param ItemComponent $compounds
     * @return ItemTemplate
     */
    public function removeCompound(ItemComponent $compounds)
    {
        $this->compounds->removeElement($compounds);

        return $this;
    }

    /**
     * Get compounds
     *
     * @return Collection
     */
    public function getCompounds()
    {
        return $this->compounds;
    }
    
    /**
     * Set visible
     *
     * @param boolean $visible
     * @return ItemTemplate
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function isEquipment() { return false; }
    public function isSkinned() { return false; }
    public function isWeapon() { return false; }
    public function isAnimal() { return false; }
    public function isPet() { return false; }
    public function isMount() { return false; }
    public function isUseable() { return false; }
}
