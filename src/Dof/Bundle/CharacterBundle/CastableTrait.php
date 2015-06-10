<?php

namespace Dof\Bundle\CharacterBundle;

use Doctrine\ORM\Mapping as ORM;

trait CastableTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="critical_hit_denominator", type="integer")
     */
    private $criticalHitDenominator;

    /**
     * @var int
     *
     * @ORM\Column(name="max_casts_per_turn", type="integer")
     */
    private $maxCastsPerTurn;

    /**
     * @var int
     *
     * @ORM\Column(name="ap_cost", type="integer")
     */
    private $apCost;

    /**
     * @var int
     *
     * @ORM\Column(name="min_cast_range", type="integer")
     */
    private $minCastRange;

    /**
     * @var int
     *
     * @ORM\Column(name="max_cast_range", type="integer")
     */
    private $maxCastRange;

    /**
     * @var bool
     *
     * @ORM\Column(name="sight_cast", type="boolean")
     */
    private $sightCast;

    /**
     * @var bool
     *
     * @ORM\Column(name="line_cast", type="boolean")
     */
    private $lineCast;

    /**
     * @var bool
     *
     * @ORM\Column(name="diagonal_cast", type="boolean")
     */
    private $diagonalCast;

    /**
     * Set criticalHitDenominator.
     *
     * @param int $criticalHitDenominator
     *
     * @return object
     */
    public function setCriticalHitDenominator($criticalHitDenominator)
    {
        $this->criticalHitDenominator = $criticalHitDenominator;

        return $this;
    }

    /**
     * Get criticalHitDenominator.
     *
     * @return int
     */
    public function getCriticalHitDenominator()
    {
        return $this->criticalHitDenominator;
    }

    /**
     * Set maxCastsPerTurn.
     *
     * @param int $maxCastsPerTurn
     *
     * @return object
     */
    public function setMaxCastsPerTurn($maxCastsPerTurn)
    {
        $this->maxCastsPerTurn = $maxCastsPerTurn;

        return $this;
    }

    /**
     * Get maxCastsPerTurn.
     *
     * @return int
     */
    public function getMaxCastsPerTurn()
    {
        return $this->maxCastsPerTurn;
    }

    /**
     * Set apCost.
     *
     * @param int $apCost
     *
     * @return object
     */
    public function setApCost($apCost)
    {
        $this->apCost = $apCost;

        return $this;
    }

    /**
     * Get apCost.
     *
     * @return int
     */
    public function getApCost()
    {
        return $this->apCost;
    }

    /**
     * Set minCastRange.
     *
     * @param int $minCastRange
     *
     * @return object
     */
    public function setMinCastRange($minCastRange)
    {
        $this->minCastRange = $minCastRange;

        return $this;
    }

    /**
     * Get minCastRange.
     *
     * @return int
     */
    public function getMinCastRange()
    {
        return $this->minCastRange;
    }

    /**
     * Set maxCastRange.
     *
     * @param int $maxCastRange
     *
     * @return object
     */
    public function setMaxCastRange($maxCastRange)
    {
        $this->maxCastRange = $maxCastRange;

        return $this;
    }

    /**
     * Get maxCastRange.
     *
     * @return int
     */
    public function getMaxCastRange()
    {
        return $this->maxCastRange;
    }

    /**
     * Set cast range.
     *
     * @param array $castRange
     *
     * @return object
     */
    public function setCastRange(array $castRange)
    {
        $this->minCastRange = $castRange['min'];
        $this->maxCastRange = $castRange['max'];

        return $this;
    }

    /**
     * Get cast range.
     *
     * @return array
     */
    public function getCastRange()
    {
        return ['min' => $this->minCastRange, 'max' => $this->maxCastRange];
    }

    /**
     * Set sightCast.
     *
     * @param bool $sightCast
     *
     * @return object
     */
    public function setSightCast($sightCast)
    {
        $this->sightCast = $sightCast;

        return $this;
    }

    /**
     * Get sightCast.
     *
     * @return bool
     */
    public function getSightCast()
    {
        return $this->sightCast;
    }

    /**
     * Get sightCast.
     *
     * @return bool
     */
    public function isSightCast()
    {
        return $this->sightCast;
    }

    /**
     * Set lineCast.
     *
     * @param bool $lineCast
     *
     * @return object
     */
    public function setLineCast($lineCast)
    {
        $this->lineCast = $lineCast;

        return $this;
    }

    /**
     * Get lineCast.
     *
     * @return bool
     */
    public function getLineCast()
    {
        return $this->lineCast;
    }

    /**
     * Get lineCast.
     *
     * @return bool
     */
    public function isLineCast()
    {
        return $this->lineCast;
    }

    /**
     * Set diagonalCast.
     *
     * @param bool $diagonalCast
     *
     * @return object
     */
    public function setDiagonalCast($diagonalCast)
    {
        $this->diagonalCast = $diagonalCast;

        return $this;
    }

    /**
     * Get diagonalCast.
     *
     * @return bool
     */
    public function getDiagonalCast()
    {
        return $this->diagonalCast;
    }

    /**
     * Get diagonalCast.
     *
     * @return bool
     */
    public function isDiagonalCast()
    {
        return $this->diagonalCast;
    }

    public function getCastArea()
    {
        return Areas::makeCastArea($this->minCastRange, $this->maxCastRange, $this->lineCast, $this->diagonalCast);
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
            'diagonalCast' => $this->diagonalCast,
            'castArea' => $this->getCastArea(),
        ] : [];
    }

    abstract public function getDamageEntries();
    abstract public function getCriticalDamageEntries();
}
