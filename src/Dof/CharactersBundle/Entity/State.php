<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * State
 *
 * @ORM\Table(name="dof_states")
 * @ORM\Entity(repositoryClass="Dof\CharactersBundle\Entity\StateRepository")
 */
class State implements IdentifiableInterface
{
    use LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prevents_spell_usage", type="boolean")
     */
    private $preventsSpellUsage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prevents_weapon_usage", type="boolean")
     */
    private $preventsWeaponUsage;

    /**
    * Set id
    *
    * @param integer $id
    * @return Spell
    */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set preventsSpellUsage
     *
     * @param boolean $preventsSpellUsage
     * @return State
     */
    public function setPreventsSpellUsage($preventsSpellUsage)
    {
        $this->preventsSpellUsage = $preventsSpellUsage;

        return $this;
    }

    /**
     * Get preventsSpellUsage
     *
     * @return boolean
     */
    public function getPreventsSpellUsage()
    {
        return $this->preventsSpellUsage;
    }

    /**
     * Set preventsWeaponUsage
     *
     * @param boolean $preventsWeaponUsage
     * @return State
     */
    public function setPreventsWeaponUsage($preventsWeaponUsage)
    {
        $this->preventsWeaponUsage = $preventsWeaponUsage;

        return $this;
    }

    /**
     * Get preventsWeaponUsage
     *
     * @return boolean
     */
    public function getPreventsWeaponUsage()
    {
        return $this->preventsWeaponUsage;
    }
    
    public function __toString()
    {
        return $this->getName();
    }
}
