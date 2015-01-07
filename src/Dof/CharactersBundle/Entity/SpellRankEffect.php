<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

use Dof\CharactersBundle\Areas;
use Dof\CharactersBundle\EffectInterface;
use Dof\CharactersBundle\EffectTrait;

use Dof\Common\GameTemplateString;

use Dof\ItemsBundle\EffectListHelper;

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
	private $hyTargets;

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
	private $hyTriggers;

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
		$this->hyTargets = $targets;
        $this->targets = array_map(function ($target) {
			if (!is_array($target))
				return $target;
			foreach ($target as &$part)
				if ($part instanceof IdentifiableInterface)
					$part = $part->getId();
			return implode('', $target);
		}, $targets);

        return $this;
    }

    /**
     * Get targets
     *
     * @return array
     */
    public function getTargets()
    {
        return $this->hyTargets ? $this->hyTargets : $this->targets;
    }
	public function getRawTargets()
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
        $this->hyTriggers = $triggers;
        $this->triggers = array_map(function ($trigger) {
			if (!is_array($trigger))
				return $trigger;
			foreach ($trigger as &$part)
				if ($part instanceof IdentifiableInterface)
					$part = $part->getId();
			return implode('', $trigger);
		}, $triggers);

        return $this;
    }

    /**
     * Get triggers
     *
     * @return array
     */
    public function getTriggers()
    {
        return $this->hyTriggers ? $this->hyTriggers : $this->triggers;
    }
	public function getRawTriggers()
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

	public function getDamageEntry()
	{
		static $map = null;
		if ($map === null)
			$map = EffectListHelper::getDamageMap();
		$eid = $this->getEffectTemplate()->getId();
		$entry = isset($map[$eid]) ? $map[$eid] : null;
		if (!$entry || $entry[0] == Element::AP_LOSS)
			return null;
		return [
			'element' => $entry[0],
			'min' => $this->getParam1(),
			'max' => $this->getParam2() ? $this->getParam2() : $this->getParam1(),
			'leech' => $entry[1]
		];
	}

	public function getDescription($locale = 'fr', $full = false, $technical = false)
	{
        $translator = $this->di->get('translator');
		$desc = $this->getEffectTemplate()->expandDescription([
			'1' => $this->getParam1(),
			'2' => $this->getParam2(),
			'3' => $this->getParam3()
		], $locale);
        if ($full) {
			if ($technical)
				array_unshift($desc, [ '[' . $this->getEffectTemplate()->getId() . '] ', GameTemplateString::COMES_FROM_TEMPLATE ]);
			$desc[] = [ ' (', GameTemplateString::COMES_FROM_TEMPLATE ];
			if ($technical || !$this->hyTargets)
				$desc[] = [ implode(', ', $this->targets), GameTemplateString::COMES_FROM_TEMPLATE ];
			else {
				$first = true;
				foreach ($this->hyTargets as $target) {
					if ($first)
						$first = false;
					else
						$desc[] = [ ', ', GameTemplateString::COMES_FROM_TEMPLATE ];
					if (!is_array($target))
						$target = [ $target ];
					$caster = $target[0] == '*';
					if ($caster)
						array_shift($target);
					$type = $target[0];
					$target[0] = null;
					$nparm = count($target) - 1;
					if ($nparm)
						$target['a'] = empty($target[1]);
					$target['c'] = $caster;
					$target['t'] = !$caster;
					array_splice($desc, count($desc), 0, GameTemplateString::expand($translator->trans('target.' . $type . $nparm, [ ], 'spell'), $target));
				}
			}
			$desc[] = [ ' ' . $translator->trans('on', [ ], 'spell') . ' ', GameTemplateString::COMES_FROM_TEMPLATE ];
			if ($technical)
				$desc[] = [ $this->areaOfEffect, GameTemplateString::COMES_FROM_TEMPLATE ];
			else {
				$aeparm = Areas::getParams($this->areaOfEffect);
				$naeparm = count($aeparm);
				// We can use {~1foo} to have "foo" on non-zero #1
				// Allow {~afoo} to have "foo" on zero #1
				for ($i = $naeparm; $i-- > 0; )
					$aeparm[chr(97 + $i)] = empty($aeparm[$i]);
				array_unshift($aeparm, null);
				array_splice($desc, count($desc), 0, GameTemplateString::expand($translator->trans('area.' . Areas::getShape($this->areaOfEffect) . $naeparm, [ ], 'spell'), $aeparm));
			}
			$desc[] = [ ')', GameTemplateString::COMES_FROM_TEMPLATE ];
	    }
        if ($this->duration)
			$desc[] = [ ' (' . $translator->transChoice('duration', $this->duration, ['%count%' => $this->duration], 'spell') . ')', GameTemplateString::COMES_FROM_TEMPLATE ];
		if ($this->delay)
			$desc[] = [ ' (' . $translator->transChoice('delay', $this->delay, ['%count%' => $this->delay], 'spell') . ')', GameTemplateString::COMES_FROM_TEMPLATE ];
		if (implode(',', $this->triggers) != 'I') {
			$triggers = [ [ $translator->trans('triggered', [ ], 'spell') . ' ', GameTemplateString::COMES_FROM_TEMPLATE ] ];
            if ($full) {
				$triggers[] = [ '(', GameTemplateString::COMES_FROM_TEMPLATE ];
				if ($technical || !$this->hyTriggers)
					$triggers[] = [ implode(', ', $this->triggers), GameTemplateString::COMES_FROM_TEMPLATE ];
				else {
					$first = true;
					foreach ($this->hyTriggers as $trigger) {
						if ($first)
							$first = false;
						else
							$triggers[] = [ ', ', GameTemplateString::COMES_FROM_TEMPLATE ];
						if (!is_array($trigger))
							$trigger = [ $trigger ];
						$type = $trigger[0];
						$trigger[0] = null;
						$nparm = count($trigger) - 1;
						if ($nparm)
							$trigger['a'] = empty($trigger[1]);
						array_splice($triggers, count($triggers), 0, GameTemplateString::expand($translator->trans(['trigger.' . $type . $nparm, 'trigger.' . $type ], [ '%n%' => $nparm], 'spell'), $trigger));
					}
				}
				$triggers[] = [ ') ', GameTemplateString::COMES_FROM_TEMPLATE ];
			}
			$triggers[] = [ ': ', GameTemplateString::COMES_FROM_TEMPLATE ];
			array_splice($desc, 0, 0, $triggers);
		}
        return GameTemplateString::clean($desc);
	}

	public function __toString()
	{
		return $this->getPlainTextDescription();
	}
}
