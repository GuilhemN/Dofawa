<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

use Dof\CharactersBundle\EffectInterface;
use Dof\CharactersBundle\EffectTrait;

use Dof\Common\GameTemplateString;

/**
 * SpellRankEffect
 *
 * @ORM\Table(name="dof_spell_rank_effects", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UQ_sre_spellrank_critical_order", columns={ "spellRank_id", "critical", "_order" })
 * })
 * @ORM\Entity(repositoryClass="SpellRankEffectRepository")
 */
class SpellRankEffect implements IdentifiableInterface, EffectInterface
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
     * @ORM\ManyToOne(targetEntity="SpellRank", inversedBy="effects")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $spellRank;

    /**
     * @var integer
     *
     * @ORM\Column(name="_order", type="integer")
     */
    private $order;

    use EffectTrait;

    /**
     * @var float
     *
     * @ORM\Column(name="probability", type="float")
     */
    private $probability;

    /**
     * @var string
     *
     * @ORM\Column(name="area_of_effect", type="string", length=255)
     */
    private $areaOfEffect;

    /**
     * @var array
     *
     * @ORM\Column(name="targets", type="simple_array")
     */
    private $targets;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="delay", type="integer")
     */
    private $delay;

    /**
     * @var array
     *
     * @ORM\Column(name="triggers", type="simple_array")
     */
    private $triggers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hidden", type="boolean")
     */
    private $hidden;

    /**
     * @var boolean
     *
     * @ORM\Column(name="critical", type="boolean")
     */
    private $critical;

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
    * @return SpellRankEffect
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
     * Set order
     *
     * @param integer $order
     * @return SpellRankEffect
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set probability
     *
     * @param float $probability
     * @return SpellRankEffect
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Get probability
     *
     * @return float
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Set areaOfEffect
     *
     * @param string $areaOfEffect
     * @return SpellRankEffect
     */
    public function setAreaOfEffect($areaOfEffect)
    {
        $this->areaOfEffect = $areaOfEffect;

        return $this;
    }

    /**
     * Get areaOfEffect
     *
     * @return string
     */
    public function getAreaOfEffect()
    {
        return $this->areaOfEffect;
    }

    /**
     * Set targets
     *
     * @param array $targets
     * @return SpellRankEffect
     */
    public function setTargets($targets)
    {
        $this->targets = $targets;

        return $this;
    }

    /**
     * Get targets
     *
     * @return array
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return SpellRankEffect
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set delay
     *
     * @param integer $delay
     * @return SpellRankEffect
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay
     *
     * @return integer
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set triggers
     *
     * @param array $triggers
     * @return SpellRankEffect
     */
    public function setTriggers($triggers)
    {
        $this->triggers = $triggers;

        return $this;
    }

    /**
     * Get triggers
     *
     * @return array
     */
    public function getTriggers()
    {
        return $this->triggers;
    }

    /**
     * Set hidden
     *
     * @param boolean $hidden
     * @return SpellRankEffect
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Set critical
     *
     * @param boolean $critical
     * @return SpellRankEffect
     */
    public function setCritical($critical)
    {
        $this->critical = $critical;

        return $this;
    }

    /**
     * Get critical
     *
     * @return boolean
     */
    public function getCritical()
    {
        return $this->critical;
    }

    /**
     * Get critical
     *
     * @return boolean
     */
    public function isCritical()
    {
        return $this->critical;
    }

	public function getDescription($locale = 'fr')
	{
		$desc = $this->getEffectTemplate()->expandDescription([
			'1' => $this->getParam1(),
			'2' => $this->getParam2(),
			'3' => $this->getParam3()
		], $locale);
		array_unshift($desc, [ '[' . $this->getEffectTemplate()->getId() . '] ', GameTemplateString::COMES_FROM_TEMPLATE ]);
		$desc[] = [ ' (' . implode(', ', $this->targets) . ' sur ' . $this->areaOfEffect . ')', GameTemplateString::COMES_FROM_TEMPLATE ];
		if ($this->duration)
			$desc[] = [ ' (' . $this->duration . ' tours)', GameTemplateString::COMES_FROM_TEMPLATE ];
		if ($this->delay)
			$desc[] = [ ' (dans ' . $this->delay . ' tours)', GameTemplateString::COMES_FROM_TEMPLATE ];
		if (implode(',', $this->triggers) != 'I')
			array_unshift($desc, [ 'Déclenché (' . implode(', ', $this->triggers) . ') : ', GameTemplateString::COMES_FROM_TEMPLATE ]);
		return $desc;
	}

    public function getNormalDescription($locale = 'fr')
    {
        $desc = $this->getEffectTemplate()->expandDescription([
            '1' => $this->getParam1(),
            '2' => $this->getParam2(),
            '3' => $this->getParam3()
        ], $locale);
        if ($this->duration)
            $desc[] = [ ' (' . $this->duration . ' tours)', GameTemplateString::COMES_FROM_TEMPLATE ];
        if ($this->delay)
            $desc[] = [ ' (dans ' . $this->delay . ' tours)', GameTemplateString::COMES_FROM_TEMPLATE ];
        if (implode(',', $this->triggers) != 'I')
            array_unshift($desc, [ 'Déclenché (' . implode(', ', $this->triggers) . ') : ', GameTemplateString::COMES_FROM_TEMPLATE ]);
        return $desc;
    }

	public function getPlainTextDescription($locale = 'fr')
	{
		return implode('', array_map(function (array $row) {
			return $row[0];
		}, $this->getDescription($locale)));
	}

	public function __toString()
	{
		return $this->getNormalDescription();
	}
}
