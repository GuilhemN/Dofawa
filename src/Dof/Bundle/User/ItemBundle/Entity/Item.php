<?php

namespace Dof\Bundle\User\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Dof\Bundle\ItemBundle\CharacteristicsTrait;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use Dof\Bundle\ItemBundle\ItemSlot;

use Dof\Bundle\User\CharacterBundle\Entity\Stuff;
use Dof\Bundle\ItemBundle\Entity\EquipmentTemplate;

/**
* Item
*
* @ORM\Table(name="dof_user_items")
* @ORM\Entity(repositoryClass="Dof\Bundle\User\ItemBundle\Entity\ItemRepository")
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
    * @var EquipmentTemplate
    *
    * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\EquipmentTemplate")
    * @ORM\JoinColumn(onDelete="CASCADE")
    */
    protected $itemTemplate;

    /**
    * @var string
    *
    * @ORM\Column(name="name", type="string", nullable=true)
    */
    protected $name;

    /**
    * @var boolean
    *
    * @ORM\Column(name="sticky", type="boolean")
    */
    protected $sticky;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="hat")
    */
    protected $stuffsHat;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="cloak")
    */
    protected $stuffsCloak;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="amulet")
    */
    protected $stuffsAmulet;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="weapon")
    */
    protected $stuffsWeapon;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="ring1")
    */
    protected $stuffsRing1;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="ring2")
    */
    protected $stuffsRing2;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="belt")
    */
    protected $stuffsBelt;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="boots")
    */
    protected $stuffsBoots;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="shield")
    */
    protected $stuffsShield;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="animal")
    */
    protected $stuffsAnimal;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="dofus1")
    */
    protected $stuffsDofus1;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="dofus2")
    */
    protected $stuffsDofus2;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="dofus3")
    */
    protected $stuffsDofus3;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="dofus4")
    */
    protected $stuffsDofus4;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="dofus5")
    */
    protected $stuffsDofus5;

    /**
    * @var Stuff
    *
    * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="dofus6")
    */
    protected $stuffsDofus6;

    public function __construct()
    {
        $this->sticky = true;

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

    /**
    * Set sticky
    *
    * @param boolean $sticky
    * @return Item
    */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
    * Get sticky
    *
    * @return boolean
    */
    public function getSticky()
    {
        return $this->sticky;
    }

    /**
    * Get sticky
    *
    * @return boolean
    */
    public function isSticky()
    {
        return $this->sticky;
    }

    public function getStuffs(){
        switch($this->itemTemplate->getType()->getSlot()){
            case ItemSlot::AMULET:
            return $this->stuffsAmulet;
            case ItemSlot::WEAPON:
            return $this->stuffsWeapon;
            case ItemSlot::RING:
            return $this->stuffsRing1->toArray() + $this->stuffsRing2->toArray();
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
            return $this->stuffsDofus1->toArray() + $this->stuffsDofus2->toArray() + $this->stuffsDofus3->toArray() + $this->stuffsDofus4->toArray() + $this->stuffsDofus5->toArray() + $this->stuffsDofus5->toArray();
        }
    }

    public function isPersonalized() { return true; }
    public function isEquipment() { return true; }
    public function isSkinned() { return false; }
    public function isAnimal() { return false; }
    public function isPet() { return false; }
    public function isMount() { return false; }
    public function isWeapon() { return false; }
    public function isCraft() { return false; }
    public function getClassId() { return 'item'; }
}
