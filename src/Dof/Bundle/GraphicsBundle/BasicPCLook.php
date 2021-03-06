<?php

namespace Dof\Bundle\GraphicsBundle;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Dof\Bundle\CharacterBundle\Entity\Breed;
use Dof\Bundle\CharacterBundle\Entity\Face;
use Dof\Bundle\ItemBundle\Entity\AnimalTemplate;
use Dof\Bundle\ItemBundle\Entity\WeaponTemplate;
use Dof\Bundle\ItemBundle\Entity\SkinnedEquipmentTemplate;
use Dof\Bundle\ItemBundle\AnimalColorizationType;
use Dof\Bundle\CharacterBundle\Gender;

/**
 * @ORM\MappedSuperclass
 */
class BasicPCLook
{
    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\CharacterBundle\Entity\Breed")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $breed;

    /**
     * @ORM\Column(name="gender", type="integer")
     */
    protected $gender;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\CharacterBundle\Entity\Face")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $face;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\WeaponTemplate")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $weapon;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\SkinnedEquipmentTemplate")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $shield;

    protected $hat;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\ItemTemplate")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $dbHat;

    /**
     * @ORM\Column(name="hat_level", type="integer", nullable=true)
     */
    protected $hatLevel;

    protected $cloak;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\ItemTemplate")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $dbCloak;

    /**
     * @ORM\Column(name="cloak_level", type="integer", nullable=true)
     */
    protected $cloakLevel;

    /**
     * @ORM\Column(name="extra_skins", type="json_array")
     */
    protected $extraSkins;

    protected $animal;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\AnimalTemplate")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $dbAnimal;

    /**
     * @ORM\Column(name="animal_is_chameleon_dragoturkey", type="boolean")
     */
    protected $animalIsChameleonDragoturkey;

    /**
     * @ORM\Column(name="colors", type="json_array")
     */
    protected $colors;

    public function __construct()
    {
        $this->extraSkins = array();
        $this->colors = array();
        $this->animalIsChameleonDragoturkey = false;
    }

    public function setBreed(Breed $breed = null)
    {
        $this->breed = $breed;

        return $this;
    }
    public function getBreed()
    {
        return $this->breed;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }
    public function getGender()
    {
        return $this->gender;
    }

    public function setFace(Face $face = null)
    {
        $this->face = $face;

        return $this;
    }
    public function getFace()
    {
        return $this->face;
    }

    public function setWeapon(WeaponTemplate $weapon = null)
    {
        $this->weapon = $weapon;

        return $this;
    }
    public function getWeapon()
    {
        return $this->weapon;
    }

    public function setShield(SkinnedEquipmentTemplate $shield = null)
    {
        $this->shield = $shield;

        return $this;
    }
    public function getShield()
    {
        return $this->shield;
    }

    public function setHat($hat = null)
    {
        $this->hat = $hat;
        if ($hat instanceof SkinnedEquipmentTemplate) {
            $this->dbHat = $hat;
            $this->hatLevel = null;
        } elseif ($hat instanceof LivingItem) {
            $this->dbHat = $hat->getTemplate();
            $this->hatLevel = $hat->getLevel();
        } else {
            $this->dbHat = null;
            $this->hatLevel = null;
        }

        return $this;
    }
    public function getHat()
    {
        return $this->hat;
    }

    public function setCloak($cloak = null)
    {
        $this->cloak = $cloak;
        if ($cloak instanceof SkinnedEquipmentTemplate) {
            $this->dbCloak = $cloak;
            $this->cloakLevel = null;
        } elseif ($cloak instanceof LivingItem) {
            $this->dbCloak = $cloak->getTemplate();
            $this->cloakLevel = $cloak->getLevel();
        } else {
            $this->dbCloak = null;
            $this->cloakLevel = null;
        }

        return $this;
    }
    public function getCloak()
    {
        return $this->cloak;
    }

    public function setExtraSkins(array $extraSkins)
    {
        $this->extraSkins = $extraSkins;

        return $this;
    }
    public function addExtraSkin($extraSkin)
    {
        $this->extraSkins[] = $extraSkin;

        return $this;
    }
    public function removeExtraSkin($extraSkin)
    {
        $key = array_search($extraSkin, $this->extraSkins);
        if ($key !== false) {
            array_splice($this->extraSkins, $key, 1);
        }

        return $this;
    }
    public function getExtraSkins()
    {
        return $this->extraSkins;
    }

    public function setAnimal($animal = null)
    {
        $this->animal = $animal;
        if ($animal instanceof AnimalTemplate) {
            $this->dbAnimal = $animal;
        } else {
            $this->dbAnimal = null;
        }
        $this->animalIsChameleonDragoturkey = $animal instanceof ChameleonDragoturkey;

        return $this;
    }
    public function getAnimal()
    {
        return $this->animal;
    }

    public function setColors(array $colors)
    {
        $this->colors = $colors;

        return $this;
    }
    public function setColor($index, $color)
    {
        if ($color === null) {
            return $this->removeColor($index);
        }
        $this->colors[$index] = $color;

        return $this;
    }
    public function removeColor($index)
    {
        unset($this->colors[$index]);

        return $this;
    }
    public function getColor($index)
    {
        return $this->colors[$index];
    }
    public function getColors()
    {
        return $this->colors;
    }

    public function toEntityLook($lodef = false)
    {
        $pcLook = new EntityLook();
        $pcLook->setBone(1);
        if ($this->getBreed() !== null && $this->gender !== null) {
            switch ($this->gender) {
                case Gender::MALE:
                    $pcLook->addSkin($lodef ? $this->getBreed()->getLodefMaleSkin() : $this->getBreed()->getMaleSkin());
                    $pcLook->setScale($this->getBreed()->getMaleSize() / 100);
                    break;
                case Gender::FEMALE:
                    $pcLook->addSkin($lodef ? $this->getBreed()->getLodefFemaleSkin() : $this->getBreed()->getFemaleSkin());
                    $pcLook->setScale($this->getBreed()->getFemaleSize() / 100);
                    break;
            }
        }
        if ($this->face !== null) {
            $pcLook->addSkin($this->face->getId());
        }
        if ($this->getHat() !== null) {
            $pcLook->addSkin($this->getHat()->getSkin());
        }
        if ($this->getCloak() !== null) {
            $pcLook->addSkin($this->getCloak()->getSkin());
        }
        if ($this->getShield() !== null) {
            $pcLook->addSkin($this->getShield()->getSkin());
        }
        if ($this->getWeapon() !== null) {
            $pcLook->addSkin($this->getWeapon()->getSkin());
        }
        foreach ($this->extraSkins as $skin) {
            $pcLook->addSkin($skin);
        }
        foreach ($this->colors as $index => $color) {
            $pcLook->setColor($index, $color);
        }
        if ($this->getAnimal() !== null && $this->getAnimal()->getBone() !== null) {
            $aniLook = new EntityLook();
            $aniLook->setBone($this->getAnimal()->getBone());
            $aniLook->setScale(($this->getAnimal()->getSize() !== null) ? $this->getAnimal()->getSize() / 100 : 1);
            switch ($this->getAnimal()->getColorizationType()) {
                case AnimalColorizationType::CHAMELEON:
                    foreach ($this->colors as $index => $color) {
                        $aniLook->setColor($index, $color);
                    }
                    break;
                case AnimalColorizationType::SHIFTED_CHAMELEON:
                    foreach ($this->colors as $index => $color) {
                        if ($index >= 3 && $index <= 5) {
                            $aniLook->setColor($index - 2, $color);
                        }
                    }
                    break;
            }
            if ($this->getAnimal()->isMount()) {
                $pcLook->setBone(2);
                foreach ($this->getAnimal()->getSkins() as $skin) {
                    $aniLook->addSkin($skin);
                }
                if ($this->getAnimal()->getColorizationType() === AnimalColorizationType::COLORS) {
                    foreach ($this->getAnimal()->getColors() as $index => $color) {
                        $aniLook->setColor($index, $color);
                    }
                }
                $aniLook->setSubEntity(2, 0, $pcLook);

                return $aniLook;
            } else {
                $pcLook->setSubEntity(1, 0, $aniLook);
            }
        }

        return $pcLook;
    }

    public function translateRelations(ObjectManager $dm, LivingItemFactory $livingItemFactory, ChameleonDragoturkey $chameleonDragoturkey)
    {
        if ($this->hatLevel !== null) {
            $this->hat = $livingItemFactory->createFromTemplateAndLevel($this->dbHat, $this->hatLevel);
        } else {
            $this->hat = $this->dbHat;
        }
        if ($this->cloakLevel !== null) {
            $this->cloak = $livingItemFactory->createFromTemplateAndLevel($this->dbCloak, $this->cloakLevel);
        } else {
            $this->cloak = $this->dbCloak;
        }
        if ($this->animalIsChameleonDragoturkey) {
            $this->animal = $chameleonDragoturkey;
        }
        if ($this->dbAnimal !== null) {
            $this->animal = $this->dbAnimal;
        }

        return $this;
    }

    public function copyFrom(BasicPCLook $look)
    {
        $this->breed = $look->breed;
        $this->gender = $look->gender;
        $this->face = $look->face;
        $this->weapon = $look->weapon;
        $this->shield = $look->shield;
        $this->hat = $look->hat;
        $this->dbHat = $look->dbHat;
        $this->hatLevel = $look->hatLevel;
        $this->cloak = $look->cloak;
        $this->dbCloak = $look->dbCloak;
        $this->cloakLevel = $look->cloakLevel;
        $this->extraSkins = $look->extraSkins;
        $this->animal = $look->animal;
        $this->dbAnimal = $look->dbAnimal;
        $this->animalIsChameleonDragoturkey = $look->animalIsChameleonDragoturkey;
        $this->colors = $look->colors;

        return $this;
    }
}
