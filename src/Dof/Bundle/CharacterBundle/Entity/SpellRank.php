<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use Dof\Bundle\CharacterBundle\CastableTrait;
use Dof\Bundle\CharacterBundle\RankDamageEffect;

/**
 * SpellRank.
 *
 * @ORM\Table(name="dof_spell_ranks", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UQ_sr_spell_rank", columns={ "spell_id", "rank" })
 * })
 * @ORM\Entity(repositoryClass="SpellRankRepository")
 */
class SpellRank implements IdentifiableInterface, TimestampableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, CastableTrait;

    /**
     * @var Spell
     *
     * @ORM\ManyToOne(targetEntity="Spell", inversedBy="ranks")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $spell;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var bool
     *
     * @ORM\Column(name="needs_free_cell", type="boolean")
     */
    private $needsFreeCell;

    /**
     * @var bool
     *
     * @ORM\Column(name="needs_taken_cell", type="boolean")
     */
    private $needsTakenCell;

    /**
     * @var bool
     *
     * @ORM\Column(name="needs_free_trap_cell", type="boolean")
     */
    private $needsFreeTrapCell;

    /**
     * @var bool
     *
     * @ORM\Column(name="modifiable_cast_range", type="boolean")
     */
    private $modifiableCastRange;

    /**
     * @var int
     *
     * @ORM\Column(name="max_effect_stack", type="integer")
     */
    private $maxEffectStack;

    /**
     * @var int
     *
     * @ORM\Column(name="max_casts_per_target", type="integer")
     */
    private $maxCastsPerTarget;

    /**
     * @var int
     *
     * @ORM\Column(name="cooldown", type="integer")
     */
    private $cooldown;

    /**
     * @var int
     *
     * @ORM\Column(name="initial_cooldown", type="integer")
     */
    private $initialCooldown;

    /**
     * @var bool
     *
     * @ORM\Column(name="global_cooldown", type="boolean")
     */
    private $globalCooldown;

    /**
     * @var int
     *
     * @ORM\Column(name="obtainment_level", type="integer")
     */
    private $obtainmentLevel;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="SpellRankStateRequirement", mappedBy="spellRank")
     */
    private $stateRequirements;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="SpellRankEffect", mappedBy="spellRank")
     * @ORM\OrderBy({ "critical" = "ASC", "order" = "ASC" })
     */
    private $effects;

    private $damageEffects;

    public function __construct()
    {
        $this->stateRequirements = new ArrayCollection();
        $this->effects = new ArrayCollection();
        $this->damageEffects = [];
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return SpellRank
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
     * Set spell.
     *
     * @param Spell $spell
     *
     * @return SpellRank
     */
    public function setSpell(Spell $spell)
    {
        $this->spell = $spell;

        return $this;
    }

    /**
     * Get spell.
     *
     * @return Spell
     */
    public function getSpell()
    {
        return $this->spell;
    }

    /**
     * Set rank.
     *
     * @param int $rank
     *
     * @return SpellRank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank.
     *
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set needsFreeCell.
     *
     * @param bool $needsFreeCell
     *
     * @return SpellRank
     */
    public function setNeedsFreeCell($needsFreeCell)
    {
        $this->needsFreeCell = $needsFreeCell;

        return $this;
    }

    /**
     * Get needsFreeCell.
     *
     * @return bool
     */
    public function getNeedsFreeCell()
    {
        return $this->needsFreeCell;
    }

    /**
     * Set needsTakenCell.
     *
     * @param bool $needsTakenCell
     *
     * @return SpellRank
     */
    public function setNeedsTakenCell($needsTakenCell)
    {
        $this->needsTakenCell = $needsTakenCell;

        return $this;
    }

    /**
     * Get needsTakenCell.
     *
     * @return bool
     */
    public function getNeedsTakenCell()
    {
        return $this->needsTakenCell;
    }

    /**
     * Set needsFreeTrapCell.
     *
     * @param bool $needsFreeTrapCell
     *
     * @return SpellRank
     */
    public function setNeedsFreeTrapCell($needsFreeTrapCell)
    {
        $this->needsFreeTrapCell = $needsFreeTrapCell;

        return $this;
    }

    /**
     * Get needsFreeTrapCell.
     *
     * @return bool
     */
    public function getNeedsFreeTrapCell()
    {
        return $this->needsFreeTrapCell;
    }

    /**
     * Set modifiableCastRange.
     *
     * @param bool $modifiableCastRange
     *
     * @return SpellRank
     */
    public function setModifiableCastRange($modifiableCastRange)
    {
        $this->modifiableCastRange = $modifiableCastRange;

        return $this;
    }

    /**
     * Get modifiableCastRange.
     *
     * @return bool
     */
    public function getModifiableCastRange()
    {
        return $this->modifiableCastRange;
    }

    /**
     * Set maxEffectStack.
     *
     * @param int $maxEffectStack
     *
     * @return SpellRank
     */
    public function setMaxEffectStack($maxEffectStack)
    {
        $this->maxEffectStack = $maxEffectStack;

        return $this;
    }

    /**
     * Get maxEffectStack.
     *
     * @return int
     */
    public function getMaxEffectStack()
    {
        return $this->maxEffectStack;
    }

    /**
     * Set maxCastsPerTarget.
     *
     * @param int $maxCastsPerTarget
     *
     * @return SpellRank
     */
    public function setMaxCastsPerTarget($maxCastsPerTarget)
    {
        $this->maxCastsPerTarget = $maxCastsPerTarget;

        return $this;
    }

    /**
     * Get maxCastsPerTarget.
     *
     * @return int
     */
    public function getMaxCastsPerTarget()
    {
        return $this->maxCastsPerTarget;
    }

    /**
     * Set cooldown.
     *
     * @param int $cooldown
     *
     * @return SpellRank
     */
    public function setCooldown($cooldown)
    {
        $this->cooldown = $cooldown;

        return $this;
    }

    /**
     * Get cooldown.
     *
     * @return int
     */
    public function getCooldown()
    {
        return $this->cooldown;
    }

    /**
     * Set initialCooldown.
     *
     * @param int $initialCooldown
     *
     * @return SpellRank
     */
    public function setInitialCooldown($initialCooldown)
    {
        $this->initialCooldown = $initialCooldown;

        return $this;
    }

    /**
     * Get initialCooldown.
     *
     * @return int
     */
    public function getInitialCooldown()
    {
        return $this->initialCooldown;
    }

    /**
     * Set globalCooldown.
     *
     * @param bool $globalCooldown
     *
     * @return SpellRank
     */
    public function setGlobalCooldown($globalCooldown)
    {
        $this->globalCooldown = $globalCooldown;

        return $this;
    }

    /**
     * Get globalCooldown.
     *
     * @return bool
     */
    public function getGlobalCooldown()
    {
        return $this->globalCooldown;
    }

    /**
     * Get globalCooldown.
     *
     * @return bool
     */
    public function isGlobalCooldown()
    {
        return $this->globalCooldown;
    }

    /**
     * Set obtainmentLevel.
     *
     * @param int $obtainmentLevel
     *
     * @return SpellRank
     */
    public function setObtainmentLevel($obtainmentLevel)
    {
        $this->obtainmentLevel = $obtainmentLevel;

        return $this;
    }

    /**
     * Get obtainmentLevel.
     *
     * @return int
     */
    public function getObtainmentLevel()
    {
        return $this->obtainmentLevel;
    }

    /**
     * Add stateRequirements.
     *
     * @param SpellRankStateRequirement $stateRequirements
     *
     * @return SpellRank
     */
    public function addStateRequirement(SpellRankStateRequirement $stateRequirements)
    {
        $this->stateRequirements[] = $stateRequirements;

        return $this;
    }

    /**
     * Remove stateRequirements.
     *
     * @param SpellRankStateRequirement $stateRequirements
     *
     * @return SpellRank
     */
    public function removeStateRequirement(SpellRankStateRequirement $stateRequirements)
    {
        $this->stateRequirements->removeElement($stateRequirements);

        return $this;
    }

    /**
     * Get stateRequirements.
     *
     * @return Collection
     */
    public function getStateRequirements()
    {
        return $this->stateRequirements;
    }

    /**
     * Add effects.
     *
     * @param SpellRankEffect $effects
     *
     * @return SpellRank
     */
    public function addEffect(SpellRankEffect $effects)
    {
        $this->effects[] = $effects;

        return $this;
    }

    /**
     * Remove effects.
     *
     * @param SpellRankEffect $effects
     *
     * @return SpellRank
     */
    public function removeEffect(SpellRankEffect $effects)
    {
        $this->effects->removeElement($effects);

        return $this;
    }

    /**
     * Get effects.
     *
     * @return Collection
     */
    public function getEffects()
    {
        return $this->effects;
    }

    public function setDamageEffects(array $de)
    {
        $this->damageEffects = $de;

        return $this;
    }
    public function getDamageEffects()
    {
        return $this->damageEffects;
    }

    public function getNormalDamageEffects()
    {
        return array_filter((array) $this->damageEffects, function ($v) {
            return !$v->isCritical();
        });
    }

    public function getCriticalDamageEffects()
    {
        return array_filter((array) $this->damageEffects, function ($v) {
            return $v->isCritical();
        });
    }

    public function getNormalEffects()
    {
        return array_filter((array) $this->effects->toArray(), function ($v) {
            return !$v->isCritical();
        });
    }

    public function getCriticalEffects()
    {
        return array_filter((array) $this->effects->toArray(), function ($v) {
            return $v->isCritical();
        });
    }

    public function getEffectsForDamage()
    {
        $dm = RankDamageEffect::getDamageMap();

        return array_filter((array) $this->effects->toArray(), function ($v) use ($dm) {
            return array_key_exists($v->getEffectTemplate()->getId(), $dm);
        });
    }

    public function getDamageEntries()
    {
        $ents = [];
        foreach ($this->effects as $row) {
            if (!$row->isCritical()) {
                $ent = $row->getDamageEntry();
                if ($ent) {
                    $ents[] = $ent;
                }
            }
        }

        return $ents;
    }

    public function getCriticalDamageEntries()
    {
        if (!$this->getCriticalHitDenominator()) {
            return $this->getDamageEntries();
        }
        $ents = [];
        foreach ($this->effects as $row) {
            if ($row->isCritical()) {
                $ent = $row->getDamageEntry();
                if ($ent) {
                    $ents[] = $ent;
                }
            }
        }

        return $ents;
    }

    public function __toString()
    {
        return $this->spell.' ['.$this->rank.']';
    }
}
