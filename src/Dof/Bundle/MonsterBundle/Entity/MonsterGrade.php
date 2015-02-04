<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

/**
 * MonsterGrade
 *
 * @ORM\Table(name="dof_monster_grades")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MonsterBundle\Entity\MonsterGradeRepository")
 */
class MonsterGrade implements IdentifiableInterface
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
    * @var Monster
    *
    * @ORM\ManyToOne(targetEntity="Monster", inversedBy="grades")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $monster;

    /**
     * @var integer
     *
     * @ORM\Column(name="grade", type="integer")
     */
    private $grade;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="lifePoints", type="integer")
     */
    private $lifePoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="actionPoints", type="integer")
     */
    private $actionPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="movementPoints", type="integer")
     */
    private $movementPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="paDodge", type="integer")
     */
    private $paDodge;

    /**
     * @var integer
     *
     * @ORM\Column(name="pmDodge", type="integer")
     */
    private $pmDodge;

    /**
     * @var integer
     *
     * @ORM\Column(name="wisdom", type="integer")
     */
    private $wisdom;

    /**
     * @var integer
     *
     * @ORM\Column(name="earthResistance", type="integer")
     */
    private $earthResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="airResistance", type="integer")
     */
    private $airResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="fireResistance", type="integer")
     */
    private $fireResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="waterResistance", type="integer")
     */
    private $waterResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="neutralResistance", type="integer")
     */
    private $neutralResistance;

    /**
     * @var integer
     *
     * @ORM\Column(name="gradeXp", type="integer")
     */
    private $gradeXp;


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
    * Set monster
    *
    * @param Monster $monster
    * @return MonsterGrade
    */
    public function setMonster(Monster $monster)
    {
        $this->monster = $monster;

        return $this;
    }

    /**
    * Get monster
    *
    * @return Monster
    */
    public function getMonster()
    {
        return $this->monster;
    }

    /**
     * Set grade
     *
     * @param integer $grade
     * @return MonsterGrade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return integer
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return MonsterGrade
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set lifePoints
     *
     * @param integer $lifePoints
     * @return MonsterGrade
     */
    public function setLifePoints($lifePoints)
    {
        $this->lifePoints = $lifePoints;

        return $this;
    }

    /**
     * Get lifePoints
     *
     * @return integer
     */
    public function getLifePoints()
    {
        return $this->lifePoints;
    }

    /**
     * Set actionPoints
     *
     * @param integer $actionPoints
     * @return MonsterGrade
     */
    public function setActionPoints($actionPoints)
    {
        $this->actionPoints = $actionPoints;

        return $this;
    }

    /**
     * Get actionPoints
     *
     * @return integer
     */
    public function getActionPoints()
    {
        return $this->actionPoints;
    }

    /**
     * Set movementPoints
     *
     * @param integer $movementPoints
     * @return MonsterGrade
     */
    public function setMovementPoints($movementPoints)
    {
        $this->movementPoints = $movementPoints;

        return $this;
    }

    /**
     * Get movementPoints
     *
     * @return integer
     */
    public function getMovementPoints()
    {
        return $this->movementPoints;
    }

    /**
     * Set paDodge
     *
     * @param integer $paDodge
     * @return MonsterGrade
     */
    public function setPaDodge($paDodge)
    {
        $this->paDodge = $paDodge;

        return $this;
    }

    /**
     * Get paDodge
     *
     * @return integer
     */
    public function getPaDodge()
    {
        return $this->paDodge;
    }

    /**
     * Set pmDodge
     *
     * @param integer $pmDodge
     * @return MonsterGrade
     */
    public function setPmDodge($pmDodge)
    {
        $this->pmDodge = $pmDodge;

        return $this;
    }

    /**
     * Get pmDodge
     *
     * @return integer
     */
    public function getPmDodge()
    {
        return $this->pmDodge;
    }

    /**
     * Set wisdom
     *
     * @param integer $wisdom
     * @return MonsterGrade
     */
    public function setWisdom($wisdom)
    {
        $this->wisdom = $wisdom;

        return $this;
    }

    /**
     * Get wisdom
     *
     * @return integer
     */
    public function getWisdom()
    {
        return $this->wisdom;
    }

    /**
     * Set earthResistance
     *
     * @param integer $earthResistance
     * @return MonsterGrade
     */
    public function setEarthResistance($earthResistance)
    {
        $this->earthResistance = $earthResistance;

        return $this;
    }

    /**
     * Get earthResistance
     *
     * @return integer
     */
    public function getEarthResistance()
    {
        return $this->earthResistance;
    }

    /**
     * Set airResistance
     *
     * @param integer $airResistance
     * @return MonsterGrade
     */
    public function setAirResistance($airResistance)
    {
        $this->airResistance = $airResistance;

        return $this;
    }

    /**
     * Get airResistance
     *
     * @return integer
     */
    public function getAirResistance()
    {
        return $this->airResistance;
    }

    /**
     * Set fireResistance
     *
     * @param integer $fireResistance
     * @return MonsterGrade
     */
    public function setFireResistance($fireResistance)
    {
        $this->fireResistance = $fireResistance;

        return $this;
    }

    /**
     * Get fireResistance
     *
     * @return integer
     */
    public function getFireResistance()
    {
        return $this->fireResistance;
    }

    /**
     * Set waterResistance
     *
     * @param integer $waterResistance
     * @return MonsterGrade
     */
    public function setWaterResistance($waterResistance)
    {
        $this->waterResistance = $waterResistance;

        return $this;
    }

    /**
     * Get waterResistance
     *
     * @return integer
     */
    public function getWaterResistance()
    {
        return $this->waterResistance;
    }

    /**
     * Set neutralResistance
     *
     * @param integer $neutralResistance
     * @return MonsterGrade
     */
    public function setNeutralResistance($neutralResistance)
    {
        $this->neutralResistance = $neutralResistance;

        return $this;
    }

    /**
     * Get neutralResistance
     *
     * @return integer
     */
    public function getNeutralResistance()
    {
        return $this->neutralResistance;
    }

    /**
     * Set gradeXp
     *
     * @param integer $gradeXp
     * @return MonsterGrade
     */
    public function setGradeXp($gradeXp)
    {
        $this->gradeXp = $gradeXp;

        return $this;
    }

    /**
     * Get gradeXp
     *
     * @return integer
     */
    public function getGradeXp()
    {
        return $this->gradeXp;
    }
}
