<?php

namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Dof\ItemsBundle\CharacteristicsTrait;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use Dof\ItemsBundle\ItemSlot;

use Dof\BuildBundle\Entity\Stuff;
use Dof\ItemsBundle\Entity\EquipmentTemplate;

/**
* Item
*
* @ORM\Table(name="dof_user_items")
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\ItemRepository")
* @ORM\InheritanceType("SINGLE_TABLE")
* @ORM\DiscriminatorColumn(name="class", type="string")
* @ORM\DiscriminatorMap({"item" = "Item", "skitem" = "SkinnedItem", "animal" = "Animal", "weapon" = "Weapon", "mount": "Mount", "pet": "Pet"})
*/
class Item implements IdentifiableInterface, OwnableInterface, TimestampableInterface
{
    use CharacteristicsTrait, TimestampableTrait, OwnableTrait;

    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @var Stuff
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsBundle\Entity\EquipmentTemplate")
    * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $itemTemplate;

    /**
    * @var string
    *
    * @ORM\Column(name="name", type="string", nullable=true)
    */
    private $name;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="hat")
    */
    private $stuffsHat;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="cloak")
    */
    private $stuffsCloak;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="amulet")
    */
    private $stuffsAmulet;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="weapon")
    */
    private $stuffsWeapon;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="ring1")
    */
    private $stuffsRing1;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="ring2")
    */
    private $stuffsRing2;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="belt")
    */
    private $stuffsBelt;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="boots")
    */
    private $stuffsBoots;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="shield")
    */
    private $stuffsShield;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="animal")
    */
    private $stuffsAnimal;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="dofus1")
    */
    private $stuffsDofus1;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="dofus2")
    */
    private $stuffsDofus2;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="dofus3")
    */
    private $stuffsDofus3;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="dofus4")
    */
    private $stuffsDofus4;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="dofus5")
    */
    private $stuffsDofus5;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="dofus6")
    */
    private $stuffsDofus6;

    public function __construct()
    {
        $this->stuffsHat = new ArrayCollection();
        $this->stuffsCloak = new ArrayCollection();
        $this->stuffsAmulet = new ArrayCollection();
        $this->stuffsWeapon = new ArrayCollection();
        $this->stuffsRing1 = new ArrayCollection();
        $this->stuffsRing2 = new ArrayCollection();
        $this->stuffsBelt = new ArrayCollection();
        $this->stuffsBoots = new ArrayCollection();
        $this->stuffsShield = new ArrayCollection();
        $this->stuffsAnimal = new ArrayCollection();

        $this->stuffsDofus1 = new ArrayCollection();
        $this->stuffsDofus2 = new ArrayCollection();
        $this->stuffsDofus3 = new ArrayCollection();
        $this->stuffsDofus4 = new ArrayCollection();
        $this->stuffsDofus5 = new ArrayCollection();
        $this->stuffsDofus6 = new ArrayCollection();
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
    * Set itemTemplate
    *
    * @param ItemTemplate $itemTemplate
    * @return Item
    */
    public function setItemTemplate(EquipmentTemplate $itemTemplate)
    {
        $this->itemTemplate = $itemTemplate;

        return $this;
    }

    /**
    * Get itemTemplate
    *
    * @return ItemTemplate
    */
    public function getItemTemplate()
    {
        return $this->itemTemplate;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Item
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

    public function getStuffs(){
        switch($this->itemTemplate->getType()->getSlot()){
            case ItemSlot::AMULET:
            return $this->stuffsAmulet;
            case ItemSlot::WEAPON:
            return $this->stuffsWeapon;
            case ItemSlot::RING:
            return $this->stuffsRing1 + $this->stuffsRing2;
            case ItemSlot::BELT:
            return $this->stuffsBelt;
            case ItemSlot::BOOTS:
            return $this->stuffsBoots;
            case ItemSlot::SHIELD:
            return $this->stuffsShield;
            case ItemSlot::HAT:
            return $this->stuffsHat;
            case ItemSlot::CLOAK:
            return $this->stuffsCloak;
            case ItemSlot::PET:
            case ItemSlot::MOUNT:
            return $this->stuffsAnimal;
            case ItemSlot::DOFUS:
            return $this->stuffsDofus1 + $this->stuffsDofus2 + $this->stuffsDofus3 + $this->stuffsDofus4 + $this->stuffsDofus5 + $this->stuffsDofus5;
        }
    }

    public function isPersonalized() { return true; }
    public function isEquipment() { return true; }
    public function isSkinned() { return false; }
    public function isAnimal() { return false; }
    public function isPet() { return false; }
    public function isMount() { return false; }
    public function isWeapon() { return false; }
    public function getClassId() { return 'item'; }
}
