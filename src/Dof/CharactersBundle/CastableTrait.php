<?php
namespace Dof\CharactersBundle;

use Doctrine\ORM\Mapping as ORM;

trait CastableTrait
{
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

    /**
     * Set criticalHitDenominator
     *
     * @param integer $criticalHitDenominator
     * @return object
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
     * @return object
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
     * @return object
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
     * @return object
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
     * @return object
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
     * @return object
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
     * @return object
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
     * @return object
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
     * @return object
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

    protected function exportCastableData($full = true)
    {
        return $full ? [
            'criticalHitDenominator' => $this->criticalHitDenominator,
            'maxCastsPerTurn' => $this->maxCastsPerTurn,
            'apCost' => $this->apCost,
            'minCastRange' => $this->minCastRange,
            'maxCastRange' => $this->maxCastRange,
            'sightCast' => $this->sightCast,
            'lineCast' => $this->lineCast,
            'diagonalCast' => $this->diagonalCast
        ] : [ ];
    }
}
