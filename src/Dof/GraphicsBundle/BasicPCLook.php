<?php

namespace Dof\GraphicsBundle;

use Dof\CharactersBundle\Entity\Breed;
use Dof\CharactersBundle\Entity\Face;
use Dof\ItemsBundle\Entity\WeaponTemplate;
use Dof\ItemsBundle\Entity\SkinnedEquipmentTemplate;
use Dof\ItemsBundle\AnimalColorizationType;
use Dof\CharactersBundle\Gender;

class BasicPCLook
{
    private $breed;
    private $gender;
    private $face;
    private $weapon;
    private $shield;
    private $hat;
    private $cloak;
    private $extraSkins;
    private $animal;
    private $colors;

    public function __construct()
    {
        $this->extraSkins = array();
        $this->colors = array();
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
        return $this;
    }
    public function getHat()
    {
        return $this->hat;
    }

    public function setCloak($cloak = null)
    {
        $this->cloak = $cloak;
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
        if ($key !== false)
            array_splice($this->extraSkins, $key, 1);
        return $this;
    }
    public function getExtraSkins()
    {
        return $this->extraSkins;
    }

    public function setAnimal($animal = null)
    {
        $this->animal = $animal;
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
        if ($color === null)
            return $this->removeColor($index);
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
        if ($this->breed !== null && $this->gender !== null) {
            switch ($this->gender) {
                case Gender::MALE:
                    $pcLook->addSkin($lodef ? $this->breed->getLodefMaleSkin() : $this->breed->getMaleSkin());
                    $pcLook->setScale($this->breed->getMaleSize() / 100);
                    break;
                case Gender::FEMALE:
                    $pcLook->addSkin($lodef ? $this->breed->getLodefFemaleSkin() : $this->breed->getFemaleSkin());
                    $pcLook->setScale($this->breed->getFemaleSize() / 100);
                    break;
            }
        }
        if ($this->face !== null)
            $pcLook->addSkin($this->face->getId());
        if ($this->hat !== null)
            $pcLook->addSkin($this->hat->getSkin());
        if ($this->cloak !== null)
            $pcLook->addSkin($this->cloak->getSkin());
        if ($this->shield !== null)
            $pcLook->addSkin($this->shield->getSkin());
        if ($this->weapon !== null)
            $pcLook->addSkin($this->weapon->getSkin());
        foreach ($this->extraSkins as $skin)
            $pcLook->addSkin($skin);
        foreach ($this->colors as $index => $color)
            $pcLook->setColor($index, $color);
        if ($this->animal !== null) {
            $aniLook = new EntityLook();
            $aniLook->setBone($this->animal->getBone());
            $aniLook->setScale($this->animal->getSize() / 100);
            switch ($this->animal->getColorizationType()) {
                case AnimalColorizationType::CHAMELEON:
                    foreach ($this->colors as $index => $color)
                        $aniLook->setColor($index, $color);
                    break;
                case AnimalColorizationType::SHIFTED_CHAMELEON:
                    foreach ($this->colors as $index => $color)
                        if ($index >= 3 && $index <= 5)
                            $aniLook->setColor($index - 2, $color);
                    break;
            }
            if ($this->animal->isMount()) {
                foreach ($this->animal->getSkins() as $skin)
                    $aniLook->addSkin($skin);
                if ($this->animal->getColorizationType() === AnimalColorizationType::COLORS)
                    foreach ($this->animal->getColors() as $index => $color)
                        $aniLook->setColor($index, $color);
                $aniLook->setSubEntity(2, 0, $pcLook);
                return $aniLook;
            } else
                $pcLook->setSubEntity(1, 0, $aniLook);
        }
        return $pcLook;
    }
}
