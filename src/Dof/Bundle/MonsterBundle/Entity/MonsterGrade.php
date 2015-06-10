<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;

/**
 * MonsterGrade.
 *
 * @ORM\Table(name="dof_monster_grades")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MonsterBundle\Entity\MonsterGradeRepository")
 */
class MonsterGrade implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Monster
     *
     * @ORM\ManyToOne(targetEntity="Monster", inversedBy="grades")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $monster;

    /**
     * @var int
     *
     * @ORM\Column(name="grade", type="integer")
     */
    private $grade;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var int
     *
     * @ORM\Column(name="lifePoints", type="integer")
     */
    private $lifePoints;

    /**
     * @var int
     *
     * @ORM\Column(name="actionPoints", type="integer")
     */
    private $actionPoints;

    /**
     * @var int
     *
     * @ORM\Column(name="movementPoints", type="integer")
     */
    private $movementPoints;

    /**
     * @var int
     *
     * @ORM\Column(name="paDodge", type="integer")
     */
    private $paDodge;

    /**
     * @var int
     *
     * @ORM\Column(name="pmDodge", type="integer")
     */
    private $pmDodge;

    /**
     * @var int
     *
     * @ORM\Column(name="wisdom", type="integer")
     */
    private $wisdom;

    /**
     * @var int
     *
     * @ORM\Column(name="earthResistance", type="integer")
     */
    private $earthResistance;

    /**
     * @var int
     *
     * @ORM\Column(name="airResistance", type="integer")
     */
    private $airResistance;

    /**
     * @var int
     *
     * @ORM\Column(name="fireResistance", type="integer")
     */
    private $fireResistance;

    /**
     * @var int
     *
     * @ORM\Column(name="waterResistance", type="integer")
     */
    private $waterResistance;

    /**
     * @var int
     *
     * @ORM\Column(name="neutralResistance", type="integer")
     */
    private $neutralResistance;

    /**
     * @var int
     *
     * @ORM\Column(name="gradeXp", type="integer")
     */
    private $gradeXp;

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
     * Set monster.
     *
     * @param Monster $monster
     *
     * @return MonsterGrade
     */
    public function setMonster(Monster $monster)
    {
        $this->monster = $monster;

        return $this;
    }

    /**
     * Get monster.
     *
     * @return Monster
     */
    public function getMonster()
    {
        return $this->monster;
    }

    /**
     * Set grade.
     *
     * @param int $grade
     *
     * @return MonsterGrade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade.
     *
     * @return int
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return MonsterGrade
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set lifePoints.
     *
     * @param int $lifePoints
     *
     * @return MonsterGrade
     */
    public function setLifePoints($lifePoints)
    {
        $this->lifePoints = $lifePoints;

        return $this;
    }

    /**
     * Get lifePoints.
     *
     * @return int
     */
    public function getLifePoints()
    {
        return $this->lifePoints;
    }

    /**
     * Set actionPoints.
     *
     * @param int $actionPoints
     *
     * @return MonsterGrade
     */
    public function setActionPoints($actionPoints)
    {
        $this->actionPoints = $actionPoints;

        return $this;
    }

    /**
     * Get actionPoints.
     *
     * @return int
     */
    public function getActionPoints()
    {
        return $this->actionPoints;
    }

    /**
     * Set movementPoints.
     *
     * @param int $movementPoints
     *
     * @return MonsterGrade
     */
    public function setMovementPoints($movementPoints)
    {
        $this->movementPoints = $movementPoints;

        return $this;
    }

    /**
     * Get movementPoints.
     *
     * @return int
     */
    public function getMovementPoints()
    {
        return $this->movementPoints;
    }

    /**
     * Set paDodge.
     *
     * @param int $paDodge
     *
     * @return MonsterGrade
     */
    public function setPaDodge($paDodge)
    {
        $this->paDodge = $paDodge;

        return $this;
    }

    /**
     * Get paDodge.
     *
     * @return int
     */
    public function getPaDodge()
    {
        return $this->paDodge;
    }

    /**
     * Set pmDodge.
     *
     * @param int $pmDodge
     *
     * @return MonsterGrade
     */
    public function setPmDodge($pmDodge)
    {
        $this->pmDodge = $pmDodge;

        return $this;
    }

    /**
     * Get pmDodge.
     *
     * @return int
     */
    public function getPmDodge()
    {
        return $this->pmDodge;
    }

    /**
     * Set wisdom.
     *
     * @param int $wisdom
     *
     * @return MonsterGrade
     */
    public function setWisdom($wisdom)
    {
        $this->wisdom = $wisdom;

        return $this;
    }

    /**
     * Get wisdom.
     *
     * @return int
     */
    public function getWisdom()
    {
        return $this->wisdom;
    }

    /**
     * Set earthResistance.
     *
     * @param int $earthResistance
     *
     * @return MonsterGrade
     */
    public function setEarthResistance($earthResistance)
    {
        $this->earthResistance = $earthResistance;

        return $this;
    }

    /**
     * Get earthResistance.
     *
     * @return int
     */
    public function getEarthResistance()
    {
        return $this->earthResistance;
    }

    /**
     * Set airResistance.
     *
     * @param int $airResistance
     *
     * @return MonsterGrade
     */
    public function setAirResistance($airResistance)
    {
        $this->airResistance = $airResistance;

        return $this;
    }

    /**
     * Get airResistance.
     *
     * @return int
     */
    public function getAirResistance()
    {
        return $this->airResistance;
    }

    /**
     * Set fireResistance.
     *
     * @param int $fireResistance
     *
     * @return MonsterGrade
     */
    public function setFireResistance($fireResistance)
    {
        $this->fireResistance = $fireResistance;

        return $this;
    }

    /**
     * Get fireResistance.
     *
     * @return int
     */
    public function getFireResistance()
    {
        return $this->fireResistance;
    }

    /**
     * Set waterResistance.
     *
     * @param int $waterResistance
     *
     * @return MonsterGrade
     */
    public function setWaterResistance($waterResistance)
    {
        $this->waterResistance = $waterResistance;

        return $this;
    }

    /**
     * Get waterResistance.
     *
     * @return int
     */
    public function getWaterResistance()
    {
        return $this->waterResistance;
    }

    /**
     * Set neutralResistance.
     *
     * @param int $neutralResistance
     *
     * @return MonsterGrade
     */
    public function setNeutralResistance($neutralResistance)
    {
        $this->neutralResistance = $neutralResistance;

        return $this;
    }

    /**
     * Get neutralResistance.
     *
     * @return int
     */
    public function getNeutralResistance()
    {
        return $this->neutralResistance;
    }

    /**
     * Set gradeXp.
     *
     * @param int $gradeXp
     *
     * @return MonsterGrade
     */
    public function setGradeXp($gradeXp)
    {
        $this->gradeXp = $gradeXp;

        return $this;
    }

    /**
     * Get gradeXp.
     *
     * @return int
     */
    public function getGradeXp()
    {
        return $this->gradeXp;
    }
}
