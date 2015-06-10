<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * State.
 *
 * @ORM\Table(name="dof_states")
 * @ORM\Entity(repositoryClass="Dof\Bundle\CharacterBundle\Entity\StateRepository")
 */
class State implements IdentifiableInterface, LocalizedNameInterface
{
    use LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="prevents_spell_usage", type="boolean")
     */
    private $preventsSpellUsage;

    /**
     * @var bool
     *
     * @ORM\Column(name="prevents_weapon_usage", type="boolean")
     */
    private $preventsWeaponUsage;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Spell
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set preventsSpellUsage.
     *
     * @param bool $preventsSpellUsage
     *
     * @return State
     */
    public function setPreventsSpellUsage($preventsSpellUsage)
    {
        $this->preventsSpellUsage = $preventsSpellUsage;

        return $this;
    }

    /**
     * Get preventsSpellUsage.
     *
     * @return bool
     */
    public function getPreventsSpellUsage()
    {
        return $this->preventsSpellUsage;
    }

    /**
     * Set preventsWeaponUsage.
     *
     * @param bool $preventsWeaponUsage
     *
     * @return State
     */
    public function setPreventsWeaponUsage($preventsWeaponUsage)
    {
        $this->preventsWeaponUsage = $preventsWeaponUsage;

        return $this;
    }

    /**
     * Get preventsWeaponUsage.
     *
     * @return bool
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
