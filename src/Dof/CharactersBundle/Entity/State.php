<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * State
 *
 * @ORM\Table(name="dof_states")
 * @ORM\Entity(repositoryClass="Dof\CharactersBundle\Entity\StateRepository")
 */
class State
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
}
