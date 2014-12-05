<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use Dof\CharactersBundle\CastableTrait;

use Dof\ItemsBundle\EffectListHelper;

/**
 * SpellRank
 *
 * @ORM\Table(name="dof_spell_ranks", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UQ_sr_spell_rank", columns={ "spell_id", "rank" })
 * })
 * @ORM\Entity(repositoryClass="SpellRankRepository")
 */
class SpellRank implements IdentifiableInterface, TimestampableInterface
{
    /**
     * @var integer
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
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var boolean
     *
     * @ORM\Column(name="needs_free_cell", type="boolean")
     */
    private $needsFreeCell;

    /**
     * @var boolean
     *
     * @ORM\Column(name="needs_taken_cell", type="boolean")
     */
    private $needsTakenCell;

    /**
     * @var boolean
     *
     * @ORM\Column(name="needs_free_trap_cell", type="boolean")
     */
    private $needsFreeTrapCell;

    /**
     * @var boolean
     *
     * @ORM\Column(name="modifiable_cast_range", type="boolean")
     */
    private $modifiableCastRange;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_effect_stack", type="integer")
     */
    private $maxEffectStack;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_casts_per_target", type="integer")
     */
    private $maxCastsPerTarget;

    /**
     * @var integer
     *
     * @ORM\Column(name="cooldown", type="integer")
     */
    private $cooldown;

    /**
     * @var integer
     *
     * @ORM\Column(name="initial_cooldown", type="integer")
     */
    private $initialCooldown;

    /**
     * @var boolean
     *
     * @ORM\Column(name="global_cooldown", type="boolean")
     */
    private $globalCooldown;

    /**
     * @var integer
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
     * Set id
     *
     * @param integer $id
     * @return SpellRank
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
     * Set spell
     *
     * @param Spell $spell
     * @return SpellRank
     */
    public function setSpell(Spell $spell)
    {
        $this->spell = $spell;

        return $this;
    }

    /**
     * Get spell
     *
     * @return Spell
     */
    public function getSpell()
    {
        return $this->spell;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return SpellRank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set needsFreeCell
     *
     * @param boolean $needsFreeCell
     * @return SpellRank
     */
    public function setNeedsFreeCell($needsFreeCell)
    {
        $this->needsFreeCell = $needsFreeCell;

        return $this;
    }

    /**
     * Get needsFreeCell
     *
     * @return boolean
     */
    public function getNeedsFreeCell()
    {
        return $this->needsFreeCell;
    }

    /**
     * Set needsTakenCell
     *
     * @param boolean $needsTakenCell
     * @return SpellRank
     */
    public function setNeedsTakenCell($needsTakenCell)
    {
        $this->needsTakenCell = $needsTakenCell;

        return $this;
    }

    /**
     * Get needsTakenCell
     *
     * @return boolean
     */
    public function getNeedsTakenCell()
    {
        return $this->needsTakenCell;
    }

    /**
     * Set needsFreeTrapCell
     *
     * @param boolean $needsFreeTrapCell
     * @return SpellRank
     */
    public function setNeedsFreeTrapCell($needsFreeTrapCell)
    {
        $this->needsFreeTrapCell = $needsFreeTrapCell;

        return $this;
    }

    /**
     * Get needsFreeTrapCell
     *
     * @return boolean
     */
    public function getNeedsFreeTrapCell()
    {
        return $this->needsFreeTrapCell;
    }

    /**
     * Set modifiableCastRange
     *
     * @param boolean $modifiableCastRange
     * @return SpellRank
     */
    public function setModifiableCastRange($modifiableCastRange)
    {
        $this->modifiableCastRange = $modifiableCastRange;

        return $this;
    }

    /**
     * Get modifiableCastRange
     *
     * @return boolean
     */
    public function getModifiableCastRange()
    {
        return $this->modifiableCastRange;
    }

    /**
     * Set maxEffectStack
     *
     * @param integer $maxEffectStack
     * @return SpellRank
     */
    public function setMaxEffectStack($maxEffectStack)
    {
        $this->maxEffectStack = $maxEffectStack;

        return $this;
    }

    /**
     * Get maxEffectStack
     *
     * @return integer
     */
    public function getMaxEffectStack()
    {
        return $this->maxEffectStack;
    }

    /**
     * Set maxCastsPerTarget
     *
     * @param integer $maxCastsPerTarget
     * @return SpellRank
     */
    public function setMaxCastsPerTarget($maxCastsPerTarget)
    {
        $this->maxCastsPerTarget = $maxCastsPerTarget;

        return $this;
    }

    /**
     * Get maxCastsPerTarget
     *
     * @return integer
     */
    public function getMaxCastsPerTarget()
    {
        return $this->maxCastsPerTarget;
    }

    /**
     * Set cooldown
     *
     * @param integer $cooldown
     * @return SpellRank
     */
    public function setCooldown($cooldown)
    {
        $this->cooldown = $cooldown;

        return $this;
    }

    /**
     * Get cooldown
     *
     * @return integer
     */
    public function getCooldown()
    {
        return $this->cooldown;
    }

    /**
     * Set initialCooldown
     *
     * @param integer $initialCooldown
     * @return SpellRank
     */
    public function setInitialCooldown($initialCooldown)
    {
        $this->initialCooldown = $initialCooldown;

        return $this;
    }

    /**
     * Get initialCooldown
     *
     * @return integer
     */
    public function getInitialCooldown()
    {
        return $this->initialCooldown;
    }

    /**
     * Set globalCooldown
     *
     * @param boolean $globalCooldown
     * @return SpellRank
     */
    public function setGlobalCooldown($globalCooldown)
    {
        $this->globalCooldown = $globalCooldown;

        return $this;
    }

    /**
    * Get globalCooldown
    *
    * @return boolean
    */
    public function getGlobalCooldown()
    {
        return $this->globalCooldown;
    }

    /**
    * Get globalCooldown
    *
    * @return boolean
    */
    public function isGlobalCooldown()
    {
        return $this->globalCooldown;
    }

    /**
     * Set obtainmentLevel
     *
     * @param integer $obtainmentLevel
     * @return SpellRank
     */
    public function setObtainmentLevel($obtainmentLevel)
    {
        $this->obtainmentLevel = $obtainmentLevel;

        return $this;
    }

    /**
     * Get obtainmentLevel
     *
     * @return integer
     */
    public function getObtainmentLevel()
    {
        return $this->obtainmentLevel;
    }

    /**
    * Add stateRequirements
    *
    * @param SpellRankStateRequirement $stateRequirements
    * @return SpellRank
    */
    public function addStateRequirement(SpellRankStateRequirement $stateRequirements)
    {
        $this->stateRequirements[] = $stateRequirements;

        return $this;
    }

    /**
    * Remove stateRequirements
    *
    * @param SpellRankStateRequirement $stateRequirements
    * @return SpellRank
    */
    public function removeStateRequirement(SpellRankStateRequirement $stateRequirements)
    {
        $this->stateRequirements->removeElement($stateRequirements);

        return $this;
    }

    /**
    * Get stateRequirements
    *
    * @return Collection
    */
    public function getStateRequirements()
    {
        return $this->stateRequirements;
    }

    /**
    * Add effects
    *
    * @param SpellRankEffect $effects
    * @return SpellRank
    */
    public function addEffect(SpellRankEffect $effects)
    {
        $this->effects[] = $effects;

        return $this;
    }

    /**
    * Remove effects
    *
    * @param SpellRankEffect $effects
    * @return SpellRank
    */
    public function removeEffect(SpellRankEffect $effects)
    {
        $this->effects->removeElement($effects);

        return $this;
    }

    /**
    * Get effects
    *
    * @return Collection
    */
    public function getEffects()
    {
        return $this->effects;
    }

    public function setDamageEffects(array $de){
        $this->damageEffects = $de;
        return $this;
    }
    public function getDamageEffects(){
        return $this->damageEffects;
    }

    public function getNormalDamageEffects()
    {
        return array_filter((array) $this->damageEffects, function($v){
            return !$v->isCritical();
        });
    }

    public function getCriticalDamageEffects()
    {
        return array_filter((array) $this->damageEffects, function($v){
            return $v->isCritical();
        });
    }

    public function getNormalEffects()
    {
        return array_filter((array) $this->effects->toArray(), function($v){
            return !$v->isCritical();
        });
    }

    public function getCriticalEffects()
    {
        return array_filter((array) $this->effects->toArray(), function($v){
            return $v->isCritical();
        });
    }

    public function getEffectsForDamage()
    {
        $dm = EffectListHelper::getDamageMap();
        return array_filter((array) $this->effects->toArray(), function($v) use ($dm){
            return array_key_exists($v->getEffectTemplate()->getId(), $dm);
        });
    }

	public function __toString()
	{
		return $this->spell . ' [' . $this->rank . ']';
	}
}
