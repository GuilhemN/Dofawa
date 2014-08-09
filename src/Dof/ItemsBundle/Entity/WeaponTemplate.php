<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

/**
 * WeaponTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\WeaponTemplateRepository")
 */
class WeaponTemplate extends SkinnedEquipmentTemplate
{
	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Dof\ItemsBundle\Entity\WeaponDamageRow", mappedBy="weapon")
     * @ORM\OrderBy({ "order" = "ASC", "id" = "ASC" })
	 */
	private $damageRows;

    /**
     * @var boolean
     *
     * @ORM\Column(name="two_handed", type="boolean")
     */
    private $twoHanded;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ethereal", type="boolean")
     */
    private $ethereal;

    /**
     * @var integer
     *
     * @ORM\Column(name="critical_hit_bonus", type="integer")
     */
    private $criticalHitBonus;

    /**
     * @var integer
     *
     * @ORM\Column(name="critical_hit_denominator", type="integer")
     */
    private $criticalHitDenominator;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_casts_per_turn", type="integer")
     */
    private $maxCastsPerTurn;

    /**
     * @var integer
     *
     * @ORM\Column(name="ap_cost", type="integer")
     */
    private $apCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_cast_range", type="integer")
     */
    private $minCastRange;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_cast_range", type="integer")
     */
    private $maxCastRange;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sight_cast", type="boolean")
     */
    private $sightCast;

    /**
     * @var boolean
     *
     * @ORM\Column(name="line_cast", type="boolean")
     */
    private $lineCast;

    /**
     * @var boolean
     *
     * @ORM\Column(name="diagonal_cast", type="boolean")
     */
    private $diagonalCast;

	public function __construct()
	{
		parent::__construct();
		$this->damageRows = new ArrayCollection();
	}

    /**
     * Add damageRows
     *
     * @param WeaponDamageRow $damageRow
     * @return WeaponTemplate
     */
    public function addDamageRow(WeaponDamageRow $damageRow)
    {
    	$this->damageRows[] = $damageRow;
    	return $this;
    }

    /**
     * Remove damageRows
     *
     * @param WeaponDamageRow $damageRow
     * @return WeaponTemplate
     */
    public function removeDamageRow(WeaponDamageRow $damageRow)
    {
    	$this->damageRows->removeElement($damageRow);
    	return $this;
    }

    /**
     * Get damageRows
     *
     * @return Collection
     */
    public function getDamageRows()
    {
    	return $this->damageRows;
    }

    /**
     * Set twoHanded
     *
     * @param boolean $twoHanded
     * @return WeaponTemplate
     */
    public function setTwoHanded($twoHanded)
    {
        $this->twoHanded = $twoHanded;

        return $this;
    }

    /**
     * Get twoHanded
     *
     * @return boolean
     */
    public function getTwoHanded()
    {
        return $this->twoHanded;
    }

    /**
     * Get twoHanded
     *
     * @return boolean
     */
    public function isTwoHanded()
    {
        return $this->twoHanded;
    }

    /**
     * Set ethereal
     *
     * @param boolean $ethereal
     * @return WeaponTemplate
     */
    public function setEthereal($ethereal)
    {
        $this->ethereal = $ethereal;

        return $this;
    }

    /**
     * Get ethereal
     *
     * @return boolean
     */
    public function getEthereal()
    {
        return $this->ethereal;
    }

    /**
     * Get ethereal
     *
     * @return boolean
     */
    public function isEthereal()
    {
        return $this->ethereal;
    }

    /**
     * Set criticalHitBonus
     *
     * @param integer $criticalHitBonus
     * @return WeaponTemplate
     */
    public function setCriticalHitBonus($criticalHitBonus)
    {
        $this->criticalHitBonus = $criticalHitBonus;

        return $this;
    }

    /**
     * Get criticalHitBonus
     *
     * @return integer
     */
    public function getCriticalHitBonus()
    {
        return $this->criticalHitBonus;
    }

    /**
     * Set criticalHitDenominator
     *
     * @param integer $criticalHitDenominator
     * @return WeaponTemplate
     */
    public function setCriticalHitDenominator($criticalHitDenominator)
    {
        $this->criticalHitDenominator = $criticalHitDenominator;

        return $this;
    }

    /**
     * Get criticalHitDenominator
     *
     * @return integer
     */
    public function getCriticalHitDenominator()
    {
        return $this->criticalHitDenominator;
    }

    /**
     * Set maxCastsPerTurn
     *
     * @param integer $maxCastsPerTurn
     * @return WeaponTemplate
     */
    public function setMaxCastsPerTurn($maxCastsPerTurn)
    {
        $this->maxCastsPerTurn = $maxCastsPerTurn;

        return $this;
    }

    /**
     * Get maxCastsPerTurn
     *
     * @return integer
     */
    public function getMaxCastsPerTurn()
    {
        return $this->maxCastsPerTurn;
    }

    /**
     * Set apCost
     *
     * @param integer $apCost
     * @return WeaponTemplate
     */
    public function setApCost($apCost)
    {
        $this->apCost = $apCost;

        return $this;
    }

    /**
     * Get apCost
     *
     * @return integer
     */
    public function getApCost()
    {
        return $this->apCost;
    }

    /**
     * Set minCastRange
     *
     * @param integer $minCastRange
     * @return WeaponTemplate
     */
    public function setMinCastRange($minCastRange)
    {
        $this->minCastRange = $minCastRange;

        return $this;
    }

    /**
     * Get minCastRange
     *
     * @return integer
     */
    public function getMinCastRange()
    {
        return $this->minCastRange;
    }

    /**
     * Set maxCastRange
     *
     * @param integer $maxCastRange
     * @return WeaponTemplate
     */
    public function setMaxCastRange($maxCastRange)
    {
        $this->maxCastRange = $maxCastRange;

        return $this;
    }

    /**
     * Get maxCastRange
     *
     * @return integer
     */
    public function getMaxCastRange()
    {
        return $this->maxCastRange;
    }

    /**
     * Set cast range
     *
     * @param array $castRange
     * @return WeaponTemplate
     */
    public function setCastRange(array $castRange)
    {
        $this->minCastRange = $castRange['min'];
        $this->maxCastRange = $castRange['max'];
        return $this;
    }

    /**
     * Get cast range
     *
     * @return array
     */
    public function getCastRange()
    {
        return [ 'min' => $this->minCastRange, 'max' => $this->maxCastRange ];
    }

    /**
     * Set sightCast
     *
     * @param boolean $sightCast
     * @return WeaponTemplate
     */
    public function setSightCast($sightCast)
    {
        $this->sightCast = $sightCast;

        return $this;
    }

    /**
     * Get sightCast
     *
     * @return boolean
     */
    public function getSightCast()
    {
        return $this->sightCast;
    }

    /**
     * Get sightCast
     *
     * @return boolean
     */
    public function isSightCast()
    {
        return $this->sightCast;
    }

    /**
     * Set lineCast
     *
     * @param boolean $lineCast
     * @return WeaponTemplate
     */
    public function setLineCast($lineCast)
    {
        $this->lineCast = $lineCast;

        return $this;
    }

    /**
     * Get lineCast
     *
     * @return boolean
     */
    public function getLineCast()
    {
        return $this->lineCast;
    }

    /**
     * Get lineCast
     *
     * @return boolean
     */
    public function isLineCast()
    {
        return $this->lineCast;
    }

    /**
     * Set diagonalCast
     *
     * @param boolean $diagonalCast
     * @return WeaponTemplate
     */
    public function setDiagonalCast($diagonalCast)
    {
        $this->diagonalCast = $diagonalCast;

        return $this;
    }

    /**
     * Get diagonalCast
     *
     * @return boolean
     */
    public function getDiagonalCast()
    {
        return $this->diagonalCast;
    }

    /**
     * Get diagonalCast
     *
     * @return boolean
     */
    public function isDiagonalCast()
    {
        return $this->diagonalCast;
    }

    public function canMage()
    {
        if (!$this->isEnhanceable())
            return false;
        foreach ($this->damageRows as $row)
            if ($row->canMage())
                return true;
        return false;
    }

	public function isWeapon() { return true; }
	public function getClassId() { return 'weapon'; }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + ($full ? [
            'damageRows' => array_map(function ($ent) { return $ent->exportData(false); }, $this->damageRows->toArray()),
            'twoHanded' => $this->twoHanded,
            'ethereal' => $this->ethereal,
            'criticalHitBonus' => $this->criticalHitBonus,
            'criticalHitDenominator' => $this->criticalHitDenominator,
            'maxCastsPerTurn' => $this->maxCastsPerTurn,
            'apCost' => $this->apCost,
            'minCastRange' => $this->minCastRange,
            'maxCastRange' => $this->maxCastRange,
            'sightCast' => $this->sightCast,
            'lineCast' => $this->lineCast,
            'diagonalCast' => $this->diagonalCast
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale))
            return true;
        return false;
    }
}
