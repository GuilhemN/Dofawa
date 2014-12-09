<?php

namespace Dof\GraphicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\GraphicsBundle\BasicPCLook;
use Dof\BuildBundle\Entity\Stuff;

/**
 * BuildLook
 *
 * @ORM\Table(name="dof_build_look")
 * @ORM\Entity(repositoryClass="Dof\GraphicsBundle\Entity\BuildLookRepository")
 */
class BuildLook extends BasicPCLook
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
     * @ORM\OneToOne(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="look")
     */
    private $stuff;


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
     * Set stuff
     *
     * @param Stuff $stuff
     * @return BuildLook
     */
    public function setStuff(Stuff $stuff)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return Stuff
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    public function getBreed()
    {
        return $this->stuff->getCharacter()->getBreed();
    }

    public function getWeapon()
    {
        $bWeapon = $this->stuff->getWeapon();
        return ($bWeapon !== null) ? $bWeapon->getMimibioteTemplate() : null;
    }

    public function getShield()
    {
        $bShield = $this->stuff->getShield();
        return ($bShield !== null) ? $bShield->getMimibioteTemplate() : null;
    }

    public function getHat()
    {
        $bHat = $this->stuff->getHat();
        return ($bHat !== null) ? $bHat->getMimibioteTemplate() : null;
    }

    public function getCloak()
    {
        $bCloak = $this->stuff->getCloak();
        return ($bCloak !== null) ? $bCloak->getMimibioteTemplate() : null;
    }

    public function getAnimal()
    {
        $bAnimal = $this->stuff->getAnimal();
        return ($bAnimal !== null) ? $bAnimal->getMimibioteTemplate() : null;
    }

    public function __clone() {
        if($this->id)
            $this->id = null;
    }
}
