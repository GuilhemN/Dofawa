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
     * @ORM\Column(name="min_action_points", type="integer")
     */
    private $minActionPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_action_points", type="integer")
     */
    private $maxActionPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_movement_points", type="integer")
     */
    private $minMovementPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_movement_points", type="integer")
     */
    private $maxMouvementPoints;

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
