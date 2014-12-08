<?php

namespace Dof\BuildBundle\Entity;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use Dof\ItemsBundle\PrimaryBonusInterface;
use Dof\ItemsBundle\PrimaryBonusTrait;

use Doctrine\ORM\Mapping as ORM;

use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\ItemsManagerBundle\Entity\PItem;
use Dof\ItemsManagerBundle\Entity\Animal;
use Dof\ItemsManagerBundle\Entity\Weapon;
use Dof\GraphicsBundle\Entity\BuildLook;

/**
 * Stuff
 *
 * @ORM\Table(name="dof_build_stuff")
 * @ORM\Entity(repositoryClass="Dof\BuildBundle\Entity\StuffRepository")
 */
class Stuff implements IdentifiableInterface, TimestampableInterface, SluggableInterface, PrimaryBonusInterface
{
    use TimestampableTrait, SluggableTrait, PrimaryBonusTrait;

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
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $hat;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $cloak;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $amulet;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Weapon", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $weapon;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $ring1;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $ring2;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $belt;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $boots;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $shield;

    /**
    * @var Animal
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Animal", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $animal;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus1;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus2;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus3;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus4;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $dofus5;

    /**
    * @var Item
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsManagerBundle\Entity\Item", nullable=true)
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
    public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array()){
        foreach($this->getItems() as $item)
            $caracts = $item->getCharacteristicsForPrimaryBonus($primaryFields, $caracts);

        return $caracts;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
