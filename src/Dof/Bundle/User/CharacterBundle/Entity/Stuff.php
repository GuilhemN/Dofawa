<?php

namespace Dof\Bundle\User\CharacterBundle\Entity;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use Doctrine\ORM\Mapping as ORM;
use Dof\Bundle\User\ItemBundle\Entity\Item as PItem;
use Dof\Bundle\User\ItemBundle\Entity\Animal;
use Dof\Bundle\User\ItemBundle\Entity\Weapon;
use Dof\Bundle\GraphicsBundle\Entity\BuildLook;
use Dof\Bundle\ItemBundle\ItemSlot;

/**
 * Stuff.
 *
 * @ORM\Table(name="dof_build_stuff")
 * @ORM\Entity(repositoryClass="Dof\Bundle\User\CharacterBundle\Entity\StuffRepository")
 */
class Stuff implements IdentifiableInterface
{
    use TimestampableTrait, SluggableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="face_label", type="string", length=1, nullable=false)
     */
    private $faceLabel;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter", inversedBy="stuffs")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $character;

    /**
     * @ORM\OneToOne(targetEntity="Dof\Bundle\GraphicsBundle\Entity\BuildLook", inversedBy="stuff")
     */
    private $look;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var int
     *
     * @ORM\Column(name="vitality", type="integer")
     */
    private $vitality;

    /**
     * @var int
     *
     * @ORM\Column(name="wisdom", type="integer")
     */
    private $wisdom;

    /**
     * @var int
     *
     * @ORM\Column(name="strength", type="integer")
     */
    private $strength;

    /**
     * @var int
     *
     * @ORM\Column(name="intelligence", type="integer")
     */
    private $intelligence;

    /**
     * @var int
     *
     * @ORM\Column(name="chance", type="integer")
     */
    private $chance;

    /**
     * @var int
     *
     * @ORM\Column(name="agility", type="integer")
     */
    private $agility;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsHat")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $hat;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsCloak")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $cloak;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsAmulet")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $amulet;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Weapon", inversedBy="stuffsWeapon")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $weapon;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsRing1")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $ring1;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsRing2")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $ring2;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsBelt")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $belt;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsBoots")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $boots;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsShield")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $shield;

    /**
     * @var Animal
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Animal", inversedBy="stuffsAnimal")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $animal;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsDofus1")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dofus1;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsDofus2")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dofus2;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsDofus3")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dofus3;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsDofus4")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dofus4;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsDofus5")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dofus5;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\User\ItemBundle\Entity\Item", inversedBy="stuffsDofus6")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dofus6;

    public function __construct()
    {
        $this->faceLabel = 'I';
        $this->vitality = 0;
        $this->wisdom = 0;
        $this->strength = 0;
        $this->intelligence = 0;
        $this->chance = 0;
        $this->agility = 0;
    }

    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
            $this->look = clone $this->look;
            $this->slug = null;

            $this->hat = null;
            $this->cloak = null;
            $this->amulet = null;
            $this->weapon = null;
            $this->ring1 = null;
            $this->ring2 = null;
            $this->belt = null;
            $this->boots = null;
            $this->shield = null;
            $this->animal = null;

            $this->dofus1 = null;
            $this->dofus2 = null;
            $this->dofus3 = null;
            $this->dofus4 = null;
            $this->dofus5 = null;
            $this->dofus6 = null;

            $this->primaryBonus = array();
            $this->createdAt = null;
        }
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return Stuff
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set faceLabel.
     *
     * @param string $faceLabel
     *
     * @return Stuff
     */
    public function setFaceLabel($faceLabel)
    {
        $this->faceLabel = $faceLabel;

        return $this;
    }

    /**
     * Get faceLabel.
     *
     * @return string
     */
    public function getFaceLabel()
    {
        return $this->faceLabel;
    }

    /**
     * Set character.
     *
     * @param PlayerCharacter $character
     *
     * @return Stuff
     */
    public function setCharacter(PlayerCharacter $character)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character.
     *
     * @return PlayerCharacter
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set look.
     *
     * @param BuildLook $look
     *
     * @return Stuff
     */
    public function setLook(BuildLook $look)
    {
        $this->look = $look;

        return $this;
    }

    /**
     * Get look.
     *
     * @return BuildLook
     */
    public function getLook()
    {
        return $this->look;
    }

    /**
     * Set visible.
     *
     * @param bool $visible
     *
     * @return Stuff
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Set vitality.
     *
     * @param int $vitality
     *
     * @return Stuff
     */
    public function setVitality($vitality)
    {
        $this->vitality = $vitality;

        return $this;
    }

    /**
     * Get vitality.
     *
     * @return int
     */
    public function getVitality()
    {
        return $this->vitality;
    }

    /**
     * Set wisdom.
     *
     * @param int $wisdom
     *
     * @return Stuff
     */
    public function setWisdom($wisdom)
    {
        $this->wisdom = $wisdom;

        return $this;
    }

    /**
     * Get wisdom.
     *
     * @return int
     */
    public function getWisdom()
    {
        return $this->wisdom;
    }

    /**
     * Set strength.
     *
     * @param int $strength
     *
     * @return Stuff
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength.
     *
     * @return int
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set intelligence.
     *
     * @param int $intelligence
     *
     * @return Stuff
     */
    public function setIntelligence($intelligence)
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    /**
     * Get intelligence.
     *
     * @return int
     */
    public function getIntelligence()
    {
        return $this->intelligence;
    }

    /**
     * Set chance.
     *
     * @param int $chance
     *
     * @return Stuff
     */
    public function setChance($chance)
    {
        $this->chance = $chance;

        return $this;
    }

    /**
     * Get chance.
     *
     * @return int
     */
    public function getChance()
    {
        return $this->chance;
    }

    /**
     * Set agility.
     *
     * @param int $agility
     *
     * @return Stuff
     */
    public function setAgility($agility)
    {
        $this->agility = $agility;

        return $this;
    }

    /**
     * Get agility.
     *
     * @return int
     */
    public function getAgility()
    {
        return $this->agility;
    }

    /**
     * Set hat.
     *
     * @param Item $hat
     *
     * @return Stuff
     */
    public function setHat(PItem $hat = null)
    {
        $this->hat = $hat;

        return $this;
    }

    /**
     * Get hat.
     *
     * @return Item
     */
    public function getHat()
    {
        return $this->hat;
    }

    /**
     * Set cloak.
     *
     * @param Item $cloak
     *
     * @return Stuff
     */
    public function setCloak(PItem $cloak = null)
    {
        $this->cloak = $cloak;

        return $this;
    }

    /**
     * Get cloak.
     *
     * @return Item
     */
    public function getCloak()
    {
        return $this->cloak;
    }

    /**
     * Set amulet.
     *
     * @param Item $amulet
     *
     * @return Stuff
     */
    public function setAmulet(PItem $amulet = null)
    {
        $this->amulet = $amulet;

        return $this;
    }

    /**
     * Get amulet.
     *
     * @return Item
     */
    public function getAmulet()
    {
        return $this->amulet;
    }

    /**
     * Set weapon.
     *
     * @param Weapon $weapon
     *
     * @return Stuff
     */
    public function setWeapon(Weapon $weapon = null)
    {
        $this->weapon = $weapon;

        return $this;
    }

    /**
     * Get weapon.
     *
     * @return Weapon
     */
    public function getWeapon()
    {
        return $this->weapon;
    }

    /**
     * Set ring1.
     *
     * @param PItem $ring1
     *
     * @return Stuff
     */
    public function setRing1(PItem $ring1 = null)
    {
        $this->ring1 = $ring1;

        return $this;
    }

    /**
     * Get ring1.
     *
     * @return PItem
     */
    public function getRing1()
    {
        return $this->ring1;
    }

    /**
     * Set ring2.
     *
     * @param PItem $ring2
     *
     * @return Stuff
     */
    public function setRing2(PItem $ring2 = null)
    {
        $this->ring2 = $ring2;

        return $this;
    }

    /**
     * Get ring2.
     *
     * @return PItem
     */
    public function getRing2()
    {
        return $this->ring2;
    }

    /**
     * Set belt.
     *
     * @param PItem $belt
     *
     * @return Stuff
     */
    public function setBelt(PItem $belt = null)
    {
        $this->belt = $belt;

        return $this;
    }

    /**
     * Get belt.
     *
     * @return PItem
     */
    public function getBelt()
    {
        return $this->belt;
    }

    /**
     * Set boots.
     *
     * @param PItem $boots
     *
     * @return Stuff
     */
    public function setBoots(PItem $boots = null)
    {
        $this->boots = $boots;

        return $this;
    }

    /**
     * Get boots.
     *
     * @return PItem
     */
    public function getBoots()
    {
        return $this->boots;
    }

    /**
     * Set shield.
     *
     * @param PItem $shield
     *
     * @return Stuff
     */
    public function setShield(PItem $shield = null)
    {
        $this->shield = $shield;

        return $this;
    }

    /**
     * Get shield.
     *
     * @return PItem
     */
    public function getShield()
    {
        return $this->shield;
    }

    /**
     * Set animal.
     *
     * @param Animal $animal
     *
     * @return Stuff
     */
    public function setAnimal(Animal $animal = null)
    {
        $this->animal = $animal;

        return $this;
    }

    /**
     * Get animal.
     *
     * @return Animal
     */
    public function getAnimal()
    {
        return $this->animal;
    }

    /**
     * Set dofus1.
     *
     * @param PItem $dofus1
     *
     * @return Stuff
     */
    public function setDofus1(PItem $dofus1 = null)
    {
        $this->dofus1 = $dofus1;

        return $this;
    }

    /**
     * Get dofus1.
     *
     * @return PItem
     */
    public function getDofus1()
    {
        return $this->dofus1;
    }

    /**
     * Set dofus2.
     *
     * @param PItem $dofus2
     *
     * @return Stuff
     */
    public function setDofus2(PItem $dofus2 = null)
    {
        $this->dofus2 = $dofus2;

        return $this;
    }

    /**
     * Get dofus2.
     *
     * @return PItem
     */
    public function getDofus2()
    {
        return $this->dofus2;
    }

    /**
     * Set dofus3.
     *
     * @param PItem $dofus3
     *
     * @return Stuff
     */
    public function setDofus3(PItem $dofus3 = null)
    {
        $this->dofus3 = $dofus3;

        return $this;
    }

    /**
     * Get dofus3.
     *
     * @return PItem
     */
    public function getDofus3()
    {
        return $this->dofus3;
    }

    /**
     * Set dofus4.
     *
     * @param PItem $dofus4
     *
     * @return Stuff
     */
    public function setDofus4(PItem $dofus4 = null)
    {
        $this->dofus4 = $dofus4;

        return $this;
    }

    /**
     * Get dofus4.
     *
     * @return PItem
     */
    public function getDofus4()
    {
        return $this->dofus4;
    }

    /**
     * Set dofus5.
     *
     * @param PItem $dofus5
     *
     * @return Stuff
     */
    public function setDofus5(PItem $dofus5 = null)
    {
        $this->dofus5 = $dofus5;

        return $this;
    }

    /**
     * Get dofus5.
     *
     * @return PItem
     */
    public function getDofus5()
    {
        return $this->dofus5;
    }

    /**
     * Set dofus6.
     *
     * @param PItem $dofus6
     *
     * @return Stuff
     */
    public function setDofus6(PItem $dofus6 = null)
    {
        $this->dofus6 = $dofus6;

        return $this;
    }

    /**
     * Get dofus6.
     *
     * @return PItem
     */
    public function getDofus6()
    {
        return $this->dofus6;
    }

    public function getItems()
    {
        return [
            'hat' => [$this->hat],
            'cloak' => [$this->cloak],
            'amulet' => [$this->amulet],
            'weapon' => [$this->weapon],
            'ring' => [
                1 => $this->ring1,
                2 => $this->ring2,
            ],
            'belt' => [$this->belt],
            'boots' => [$this->boots],
            'shield' => [$this->shield],
            'animal' => [$this->animal],
            'dofus' => [
                1 => $this->dofus1,
                2 => $this->dofus2,
                3 => $this->dofus3,
                4 => $this->dofus4,
                5 => $this->dofus5,
                6 => $this->dofus6,
                ],

        ];
    }

    public function getItemType(PItem $item, $slot = 0)
    {
        $slot = intval($slot);
        switch ($item->getItemTemplate()->getType()->getSlot()) {
            case ItemSlot::AMULET:
                return 'amulet';
            case ItemSlot::WEAPON:
                return 'weapon';
            case ItemSlot::RING:
                if ($slot == 2) {
                    return 'ring2';
                } else {
                    return 'ring1';
                }
                break;
            case ItemSlot::BELT:
                return 'belt';
            case ItemSlot::BOOTS:
                return 'boots';
            case ItemSlot::SHIELD:
                return 'shield';
            case ItemSlot::HAT:
                return 'hat';
            case ItemSlot::CLOAK:
                return 'cloak';
            case ItemSlot::DOFUS:
                if ($slot >= 1 && $slot <= 6) {
                    return 'dofus'.$slot;
                } else {
                    return 'dofus1';
                }
            default:
                if ($item->isAnimal()) {
                    return 'animal';
                } else {
                    return false;
                }
        }
    }

    public function canSee()
    {
        return $this->getCharacter()->canSee() && ($this->isVisible() || $this->canWrite());
    }

    public function canWrite()
    {
        return $this->getCharacter()->canWrite();
    }

    public function __toString()
    {
        return $this->getName();
    }
}
