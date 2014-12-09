<?php

namespace Dof\BuildBundle\Entity;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use Doctrine\ORM\Mapping as ORM;

use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\ItemsManagerBundle\Entity\Item as PItem;
use Dof\ItemsManagerBundle\Entity\Animal;
use Dof\ItemsManagerBundle\Entity\Weapon;
use Dof\GraphicsBundle\Entity\BuildLook;

use Dof\ItemsBundle\ItemSlot;

/**
 * Stuff
 *
 * @ORM\Table(name="dof_build_stuff")
 * @ORM\Entity(repositoryClass="Dof\BuildBundle\Entity\StuffRepository")
 */
class Stuff implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    use TimestampableTrait, SluggableTrait;

    /**
     * @var integer
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
    private $faceLabel = 'I';

    /**
     * @ORM\ManyToOne(targetEntity="Dof\BuildBundle\Entity\PlayerCharacter", inversedBy="stuffs")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $character;

    /**
     * @ORM\OneToOne(targetEntity="Dof\GraphicsBundle\Entity\BuildLook", inversedBy="stuff")
     */
    private $look;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var integer
     *
     * @ORM\Column(name="vitality", type="integer")
     */
    private $vitality;

    /**
     * @var integer
     *
     * @ORM\Column(name="wisdom", type="integer")
     */
    private $wisdom;

    /**
     * @var integer
     *
     * @ORM\Column(name="strength", type="integer")
     */
    private $strength;

    /**
     * @var integer
     *
     * @ORM\Column(name="intelligence", type="integer")
     */
    private $intelligence;

    /**
     * @var integer
     *
     * @ORM\Column(name="chance", type="integer")
     */
    private $chance;

    /**
     * @var integer
     *
     * @ORM\Column(name="agility", type="integer")
     */
    private $agility;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $hat;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $cloak;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $amulet;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Weapon")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $weapon;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $ring1;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $ring2;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $belt;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $boots;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $shield;

    /**
    * @var Animal
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Animal")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $animal;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus1;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus2;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus3;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus4;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus5;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus6;

    public function __construct()
    {
        $this->vitality = 0;
        $this->wisdom = 0;
        $this->strength = 0;
        $this->intelligence = 0;
        $this->chance = 0;
        $this->agility = 0;
    }

    public function __clone()
    {
        if($this->id){
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
     * @return Stuff
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
     * Set faceLabel
     *
     * @param string $faceLabel
     * @return Stuff
     */
    public function setFaceLabel($faceLabel)
    {
        $this->faceLabel = $faceLabel;

        return $this;
    }

    /**
     * Get faceLabel
     *
     * @return string
     */
    public function getFaceLabel()
    {
        return $this->faceLabel;
    }

    /**
     * Set character
     *
     * @param PlayerCharacter $character
     * @return Stuff
     */
    public function setCharacter(PlayerCharacter $character)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character
     *
     * @return PlayerCharacter
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set look
     *
     * @param BuildLook $look
     * @return Stuff
     */
    public function setLook(BuildLook $look)
    {
        $this->look = $look;

        return $this;
    }

    /**
     * Get look
     *
     * @return BuildLook
     */
    public function getLook()
    {
        return $this->look;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Stuff
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

    /**
     * Set vitality
     *
     * @param integer $vitality
     * @return Stuff
     */
    public function setVitality($vitality)
    {
        $this->vitality = $vitality;

        return $this;
    }

    /**
     * Get vitality
     *
     * @return integer
     */
    public function getVitality()
    {
        return $this->vitality;
    }

    /**
     * Set wisdom
     *
     * @param integer $wisdom
     * @return Stuff
     */
    public function setWisdom($wisdom)
    {
        $this->wisdom = $wisdom;

        return $this;
    }

    /**
     * Get wisdom
     *
     * @return integer
     */
    public function getWisdom()
    {
        return $this->wisdom;
    }

    /**
     * Set strength
     *
     * @param integer $strength
     * @return Stuff
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return integer
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set intelligence
     *
     * @param integer $intelligence
     * @return Stuff
     */
    public function setIntelligence($intelligence)
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    /**
     * Get intelligence
     *
     * @return integer
     */
    public function getIntelligence()
    {
        return $this->intelligence;
    }

    /**
     * Set chance
     *
     * @param integer $chance
     * @return Stuff
     */
    public function setChance($chance)
    {
        $this->chance = $chance;

        return $this;
    }

    /**
     * Get chance
     *
     * @return integer
     */
    public function getChance()
    {
        return $this->chance;
    }

    /**
     * Set agility
     *
     * @param integer $agility
     * @return Stuff
     */
    public function setAgility($agility)
    {
        $this->agility = $agility;

        return $this;
    }

    /**
     * Get agility
     *
     * @return integer
     */
    public function getAgility()
    {
        return $this->agility;
    }

    /**
    * Set hat
    *
    * @param Item $hat
    * @return Stuff
    */
    public function setHat(PItem $hat = null)
    {
        $this->hat = $hat;

        return $this;
    }

    /**
    * Get hat
    *
    * @return Item
    */
    public function getHat()
    {
        return $this->hat;
    }

    /**
    * Set cloak
    *
    * @param Item $cloak
    * @return Stuff
    */
    public function setCloak(PItem $cloak = null)
    {
        $this->cloak = $cloak;

        return $this;
    }

    /**
    * Get cloak
    *
    * @return Item
    */
    public function getCloak()
    {
        return $this->cloak;
    }

    /**
    * Set amulet
    *
    * @param Item $amulet
    * @return Stuff
    */
    public function setAmulet(PItem $amulet = null)
    {
        $this->amulet = $amulet;

        return $this;
    }

    /**
    * Get amulet
    *
    * @return Item
    */
    public function getAmulet()
    {
        return $this->amulet;
    }

    /**
    * Set weapon
    *
    * @param Weapon $weapon
    * @return Stuff
    */
    public function setWeapon(Weapon $weapon = null)
    {
        $this->weapon = $weapon;

        return $this;
    }

    /**
    * Get weapon
    *
    * @return Weapon
    */
    public function getWeapon()
    {
        return $this->weapon;
    }

    /**
    * Set ring1
    *
    * @param PItem $ring1
    * @return Stuff
    */
    public function setRing1(PItem $ring1 = null)
    {
        $this->ring1 = $ring1;

        return $this;
    }

    /**
    * Get ring1
    *
    * @return PItem
    */
    public function getRing1()
    {
        return $this->ring1;
    }

    /**
    * Set ring2
    *
    * @param PItem $ring2
    * @return Stuff
    */
    public function setRing2(PItem $ring2 = null)
    {
        $this->ring2 = $ring2;

        return $this;
    }

    /**
    * Get ring2
    *
    * @return PItem
    */
    public function getRing2()
    {
        return $this->ring2;
    }

    /**
    * Set belt
    *
    * @param PItem $belt
    * @return Stuff
    */
    public function setBelt(PItem $belt = null)
    {
        $this->belt = $belt;

        return $this;
    }

    /**
    * Get belt
    *
    * @return PItem
    */
    public function getBelt()
    {
        return $this->belt;
    }

    /**
    * Set boots
    *
    * @param PItem $boots
    * @return Stuff
    */
    public function setBoots(PItem $boots = null)
    {
        $this->boots = $boots;

        return $this;
    }

    /**
    * Get boots
    *
    * @return PItem
    */
    public function getBoots()
    {
        return $this->boots;
    }

    /**
    * Set shield
    *
    * @param PItem $shield
    * @return Stuff
    */
    public function setShield(PItem $shield = null)
    {
        $this->shield = $shield;

        return $this;
    }

    /**
    * Get shield
    *
    * @return PItem
    */
    public function getShield()
    {
        return $this->shield;
    }

    /**
    * Set dofus1
    *
    * @param PItem $dofus1
    * @return Stuff
    */
    public function setDofus1(PItem $dofus1 = null)
    {
        $this->dofus1 = $dofus1;

        return $this;
    }

    /**
    * Get dofus1
    *
    * @return PItem
    */
    public function getDofus1()
    {
        return $this->dofus1;
    }

    /**
    * Set dofus2
    *
    * @param PItem $dofus2
    * @return Stuff
    */
    public function setDofus2(PItem $dofus2 = null)
    {
        $this->dofus2 = $dofus2;

        return $this;
    }

    /**
    * Get dofus2
    *
    * @return PItem
    */
    public function getDofus2()
    {
        return $this->dofus2;
    }

    /**
    * Set dofus3
    *
    * @param PItem $dofus3
    * @return Stuff
    */
    public function setDofus3(PItem $dofus3 = null)
    {
        $this->dofus3 = $dofus3;

        return $this;
    }

    /**
    * Get dofus3
    *
    * @return PItem
    */
    public function getDofus3()
    {
        return $this->dofus3;
    }

    /**
    * Set dofus4
    *
    * @param PItem $dofus4
    * @return Stuff
    */
    public function setDofus4(PItem $dofus4 = null)
    {
        $this->dofus4 = $dofus4;

        return $this;
    }

    /**
    * Get dofus4
    *
    * @return PItem
    */
    public function getDofus4()
    {
        return $this->dofus4;
    }

    /**
    * Set dofus5
    *
    * @param PItem $dofus5
    * @return Stuff
    */
    public function setDofus5(PItem $dofus5 = null)
    {
        $this->dofus5 = $dofus5;

        return $this;
    }

    /**
    * Get dofus5
    *
    * @return PItem
    */
    public function getDofus5()
    {
        return $this->dofus5;
    }

    /**
    * Set dofus6
    *
    * @param PItem $dofus6
    * @return Stuff
    */
    public function setDofus6(PItem $dofus6 = null)
    {
        $this->dofus6 = $dofus6;

        return $this;
    }

    /**
    * Get dofus6
    *
    * @return PItem
    */
    public function getDofus6()
    {
        return $this->dofus6;
    }

    public function getItems(){
        return [
            'hat' => [ $this->hat ],
            'cloak' => [ $this->cloak ],
            'amulet' => [ $this->amulet ],
            'weapon' => [ $this->weapon ],
            'ring' => [
                1 => $this->ring1,
                2 => $this->ring2
            ],
            'belt' => [ $this->belt ],
            'boots' => [ $this->boots ],
            'shield' => [ $this->shield ],
            'animal' => [ $this->animal ],
            'dofus' => [
                1 => $this->dofus1,
                2 => $this->dofus2,
                3 => $this->dofus3,
                4 => $this->dofus4,
                5 => $this->dofus5,
                6 => $this->dofus6,
                ]

        ];
    }

    public function addItem(PItem $item, $slot = 0){
        $slot = intval($slot);
        switch ($item->getItemTemplate()->getType()->getSlot()) {
            case ItemSlot::AMULET:
                $lItem = $this->getAmulet();
                $this->setAmulet($item);
                break;
            case ItemSlot::WEAPON:
                $lItem = $this->getWeapon();
                $this->setWeapon($item);
                $this->getLook()->setWeapon($item->getItemTemplate());
                break;
            case ItemSlot::RING:
                if($slot == 2){
                    $lItem = $this->getRing2();
                    $this->setRing2($item);
                }
                else{
                    $lItem = $this->getRing1();
                    $this->setRing1($item);
                }
                break;
            case ItemSlot::BELT:
                $lItem = $this->getBelt();
                $this->setBelt($item);
                break;
            case ItemSlot::BOOTS:
                $lItem = $this->getBoots();
                $this->setBoots($item);
                break;
            case ItemSlot::SHIELD:
                $lItem = $this->getShield();
                $this->setShield($item);
                $this->getLook()->setShield($item->getItemTemplate());
                break;
            case ItemSlot::HAT:
                $lItem = $this->getHat();
                $this->setHat($item);
                $this->getLook()->setHat($item->getItemTemplate());
                break;
            case ItemSlot::CLOAK:
                $lItem = $this->getCloak();
                $this->setCloak($item);
                $this->getLook()->setCloak($item->getItemTemplate());
                break;
            case ItemSlot::PET:
            case ItemSlot::MOUNT:
                $lItem = $this->getAnimal();
                $this->setAnimal($item);
                $this->getLook()->setAnimal($item->getItemTemplate());
                break;
            case ItemSlot::DOFUS:
                if($slot >= 1 && $slot <= 6){
                    $lItem = $this->{ 'getDofus' . $slot }();
                    $this->{ 'setDofus' . $slot }($item);
                }
                else{
                    $lItem = $this->getDofus1();
                    $this->setDofus1($item);
                }
                break;
        }
        return $lItem;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
