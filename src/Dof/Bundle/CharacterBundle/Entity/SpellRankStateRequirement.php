<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

/**
 * SpellRankStateRequirement
 *
 * @ORM\Table(name="dof_spell_rank_state_requirements", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UQ_srsr_spellrank_state", columns={ "spellRank_id", "state_id" })
 * })
 * @ORM\Entity(repositoryClass="SpellRankStateRequirementRepository")
 */
class SpellRankStateRequirement implements IdentifiableInterface
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
     * @var SpellRank
     *
     * @ORM\ManyToOne(targetEntity="SpellRank", inversedBy="stateRequirements")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $spellRank;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $state;

    /**
     * @var boolean
     *
     * @ORM\Column(name="requirement", type="boolean")
     */
    private $requirement;

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
     * Set spellRank
     *
     * @param SpellRank $spellRank
     * @return SpellRankStateRequirement
     */
    public function setSpellRank(SpellRank $spellRank)
    {
        $this->spellRank = $spellRank;

        return $this;
    }

    /**
     * Get spellRank
     *
     * @return SpellRank
     */
    public function getSpellRank()
    {
        return $this->spellRank;
    }

    /**
     * Set state
     *
     * @param State $state
     * @return SpellRankStateRequirement
     */
    public function setState(State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set requirement
     *
     * @param boolean $requirement
     * @return SpellRankStateRequirement
     */
    public function setRequirement($requirement)
    {
        $this->requirement = $requirement;

        return $this;
    }

    /**
     * Get requirement
     *
     * @return boolean
     */
    public function getRequirement()
    {
        return $this->requirement;
    }
}
