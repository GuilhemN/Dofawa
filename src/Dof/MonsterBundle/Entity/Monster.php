<?php

namespace Dof\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;
/**
 * Monster
 *
 * @ORM\Table(name="dof_monsters")
 * @ORM\Entity(repositoryClass="Dof\MonsterBundle\Entity\MonsterRepository")
 */
class Monster implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_level", type="integer")
     */
    private $minLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_level", type="integer")
     */
    private $maxLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_life_points", type="integer")
     */
    private $minLifePoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_life_points", type="integer")
     */
    private $maxLifePoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_action_points", type="integer", nullable=true)
     */
    private $minActionPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_action_points", type="integer", nullable=true)
     */
    private $maxActionPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_movement_points", type="integer", nullable=true)
     */
    private $minMovementPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_movement_points", type="integer", nullable=true)
     */
    private $maxMovementPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_earth_resistance", type="integer")
     */
    private $minEarthResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_earth_resistance", type="integer")
     */
    private $maxEarthResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_air_resistance", type="integer")
     */
    private $minAirResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_air_resistance", type="integer")
     */
    private $maxAirResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_fire_resistance", type="integer")
     */
    private $minFireResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_fire_resistance", type="integer")
     */
    private $maxFireResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_water_resistance", type="integer")
     */
    private $minWaterResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_water_resistance", type="integer")
     */
    private $maxWaterResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_neutral_resistance", type="integer")
     */
    private $minNeutralResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_neutral_resistance", type="integer")
     */
    private $maxNeutralResistance;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * Set id
     *
     * @param integer $id
     * @return Monster
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
     * Set minLevel
     *
     * @param integer $minLevel
     * @return Monster
     */
    public function setMinLevel($minLevel)
    {
        $this->minLevel = $minLevel;

        return $this;
    }

    /**
     * Get minLevel
     *
     * @return integer
     */
    public function getMinLevel()
    {
        return $this->minLevel;
    }

    /**
     * Set maxLevel
     *
     * @param integer $maxLevel
     * @return Monster
     */
    public function setMaxLevel($maxLevel)
    {
        $this->maxLevel = $maxLevel;

        return $this;
    }

    /**
     * Get maxLevel
     *
     * @return integer
     */
    public function getMaxLevel()
    {
        return $this->maxLevel;
    }


    /**
     * Set minLifePoints
     *
     * @param integer $minLifePoints
     * @return Monster
     */
    public function setMinLifePoints($minLifePoints)
    {
        $this->minLifePoints = $minLifePoints;

        return $this;
    }

    /**
     * Get minLifePoints
     *
     * @return integer
     */
    public function getMinLifePoints()
    {
        return $this->minLifePoints;
    }

    /**
     * Set maxLifePoints
     *
     * @param integer $maxLifePoints
     * @return Monster
     */
    public function setMaxLifePoints($maxLifePoints)
    {
        $this->maxLifePoints = $maxLifePoints;

        return $this;
    }

    /**
     * Get maxLifePoints
     *
     * @return integer
     */
    public function getMaxLifePoints()
    {
        return $this->maxLifePoints;
    }

    /**
     * Set minActionPoints
     *
     * @param integer $minActionPoints
     * @return Monster
     */
    public function setMinActionPoints($minActionPoints)
    {
        $this->minActionPoints = $minActionPoints;

        return $this;
    }

    /**
     * Get minActionPoints
     *
     * @return integer
     */
    public function getMinActionPoints()
    {
        return $this->minActionPoints;
    }

    /**
     * Set maxActionPoints
     *
     * @param integer $maxActionPoints
     * @return Monster
     */
    public function setMaxActionPoints($maxActionPoints)
    {
        $this->maxActionPoints = $maxActionPoints;

        return $this;
    }

    /**
     * Get maxActionPoints
     *
     * @return integer
     */
    public function getMaxActionPoints()
    {
        return $this->maxActionPoints;
    }

    /**
     * Set minMovementPoints
     *
     * @param integer $minMovementPoints
     * @return Monster
     */
    public function setMinMovementPoints($minMovementPoints)
    {
        $this->minMovementPoints = $minMovementPoints;

        return $this;
    }

    /**
     * Get minMovementPoints
     *
     * @return integer
     */
    public function getMinMovementPoints()
    {
        return $this->minMovementPoints;
    }

    /**
     * Set maxMovementPoints
     *
     * @param integer $maxMovementPoints
     * @return Monster
     */
    public function setMaxMovementPoints($maxMovementPoints)
    {
        $this->maxMovementPoints = $maxMovementPoints;

        return $this;
    }

    /**
     * Get maxMovementPoints
     *
     * @return integer
     */
    public function getMaxMovementPoints()
    {
        return $this->maxMovementPoints;
    }

    /**
     * Set minEarthResistance
     *
     * @param integer $minEarthResistance
     * @return Monster
     */
    public function setMinEarthResistance($minEarthResistance)
    {
        $this->minEarthResistance = $minEarthResistance;

        return $this;
    }

    /**
     * Get minEarthResistance
     *
     * @return integer
     */
    public function getMinEarthResistance()
    {
        return $this->minEarthResistance;
    }

    /**
     * Set maxEarthResistance
     *
     * @param integer $maxEarthResistance
     * @return Monster
     */
    public function setMaxEarthResistance($maxEarthResistance)
    {
        $this->maxEarthResistance = $maxEarthResistance;

        return $this;
    }

    /**
     * Get maxEarthResistance
     *
     * @return integer
     */
    public function getMaxEarthResistance()
    {
        return $this->maxEarthResistance;
    }

    /**
     * Set minAirResistance
     *
     * @param integer $minAirResistance
     * @return Monster
     */
    public function setMinAirResistance($minAirResistance)
    {
        $this->minAirResistance = $minAirResistance;

        return $this;
    }

    /**
     * Get minAirResistance
     *
     * @return integer
     */
    public function getMinAirResistance()
    {
        return $this->minAirResistance;
    }

    /**
     * Set maxAirResistance
     *
     * @param integer $maxAirResistance
     * @return Monster
     */
    public function setMaxAirResistance($maxAirResistance)
    {
        $this->maxAirResistance = $maxAirResistance;

        return $this;
    }

    /**
     * Get maxAirResistance
     *
     * @return integer
     */
    public function getMaxAirResistance()
    {
        return $this->maxAirResistance;
    }

    /**
     * Set minFireResistance
     *
     * @param integer $minFireResistance
     * @return Monster
     */
    public function setMinFireResistance($minFireResistance)
    {
        $this->minFireResistance = $minFireResistance;

        return $this;
    }

    /**
     * Get minFireResistance
     *
     * @return integer
     */
    public function getMinFireResistance()
    {
        return $this->minFireResistance;
    }

    /**
     * Set maxFireResistance
     *
     * @param integer $maxFireResistance
     * @return Monster
     */
    public function setMaxFireResistance($maxFireResistance)
    {
        $this->maxFireResistance = $maxFireResistance;

        return $this;
    }

    /**
     * Get maxFireResistance
     *
     * @return integer
     */
    public function getMaxFireResistance()
    {
        return $this->maxFireResistance;
    }

    /**
     * Set minWaterResistance
     *
     * @param integer $minWaterResistance
     * @return Monster
     */
    public function setMinWaterResistance($minWaterResistance)
    {
        $this->minWaterResistance = $minWaterResistance;

        return $this;
    }

    /**
     * Get minWaterResistance
     *
     * @return integer
     */
    public function getMinWaterResistance()
    {
        return $this->minWaterResistance;
    }

    /**
     * Set maxWaterResistance
     *
     * @param integer $maxWaterResistance
     * @return Monster
     */
    public function setMaxWaterResistance($maxWaterResistance)
    {
        $this->maxWaterResistance = $maxWaterResistance;

        return $this;
    }

    /**
     * Get maxWaterResistance
     *
     * @return integer
     */
    public function getMaxWaterResistance()
    {
        return $this->maxWaterResistance;
    }

    /**
     * Set minNeutralResistance
     *
     * @param integer $minNeutralResistance
     * @return Monster
     */
    public function setMinNeutralResistance($minNeutralResistance)
    {
        $this->minNeutralResistance = $minNeutralResistance;

        return $this;
    }

    /**
     * Get minNeutralResistance
     *
     * @return integer
     */
    public function getMinNeutralResistance()
    {
        return $this->minNeutralResistance;
    }

    /**
     * Set maxNeutralResistance
     *
     * @param integer $maxNeutralResistance
     * @return Monster
     */
    public function setMaxNeutralResistance($maxNeutralResistance)
    {
        $this->maxNeutralResistance = $maxNeutralResistance;

        return $this;
    }

    /**
     * Get maxNeutralResistance
     *
     * @return integer
     */
    public function getMaxNeutralResistance()
    {
        return $this->maxNeutralResistance;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Monster
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
