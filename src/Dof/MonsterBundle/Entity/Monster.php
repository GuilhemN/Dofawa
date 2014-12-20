<?php

namespace Dof\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * Monster
 *
 * @ORM\Table(name="dof_monsters")
 * @ORM\Entity(repositoryClass="Dof\MonsterBundle\Entity\MonsterRepository")
 */
class Monster implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
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
    * @var MonsterRace
    *
    * @ORM\ManyToOne(targetEntity="MonsterRace", inversedBy="monsters")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $race;

    /**
    * @ORM\OneToMany(targetEntity="MonsterGrade", mappedBy="monster")
    * @ORM\JoinColumn(nullable=true)
    */
    private $grades;

    /**
    * @ORM\OneToMany(targetEntity="MonsterDrop", mappedBy="monster")
    * @ORM\JoinColumn(nullable=true)
    */
    private $drops;

    /**
    * @var Monster
    *
    * @ORM\ManyToOne(targetEntity="Monster", inversedBy="normalMonster")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $archMonster;

    /**
    * @var Monster
    *
    * @ORM\OneToOne(targetEntity="Monster", mappedBy="archMonster")
    * @ORM\JoinColumn(nullable=true)
    */
    private $normalMonster;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
        $this->drops = new ArrayCollection();
    }

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
    * Set race
    *
    * @param MonsterRace $race
    * @return Monster
    */
    public function setRace(MonsterRace $race)
    {
        $this->race = $race;

        return $this;
    }

    /**
    * Get race
    *
    * @return MonsterRace
    */
    public function getRace()
    {
        return $this->race;
    }

    /**
    * Add grades
    *
    * @param MonsterGrade $grades
    * @return Monster
    */
    public function addGrade(MonsterGrade $grades)
    {
        $this->grades[] = $grades;

        return $this;
    }

    /**
    * Remove grades
    *
    * @param MonsterGrade $grades
    * @return Monster
    */
    public function removeGrade(MonsterGrade $grades)
    {
        $this->grades->removeElement($grades);

        return $this;
    }

    /**
    * Get grades
    *
    * @return Collection
    */
    public function getGrades()
    {
        return $this->grades;
    }

    public function getMinGrade(){
        $min = null; $minGrade = null;
        foreach($this->grades as $grade)
            if($min === null or $min > $grade->getGrade()){
                $min = $grade->getGrade();
                $minGrade = $grade;
            }

        return $minGrade;
    }

    public function getMaxGrade(){
        $max = null; $maxGrade = null;
        foreach($this->grades as $grade)
            if($max === null or $max < $grade->getGrade()){
                $max = $grade->getGrade();
                $maxGrade = $grade;
            }

        return $maxGrade;
    }

    /**
    * Add drops
    *
    * @param MonsterDrop $drops
    * @return Monster
    */
    public function addDrop(MonsterDrop $drops)
    {
        $this->drops[] = $drops;

        return $this;
    }

    /**
    * Remove drops
    *
    * @param MonsterDrop $drops
    * @return Monster
    */
    public function removeDrop(MonsterDrop $drops)
    {
        $this->drops->removeElement($drops);

        return $this;
    }

    /**
    * Get drops
    *
    * @return Collection
    */
    public function getDrops()
    {
        return $this->drops;
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

    /**
    * Set archMonster
    *
    * @param Monster $archMonster
    * @return Monster
    */
    public function setArchMonster(Monster $archMonster = null)
    {
        $this->archMonster = $archMonster;

        return $this;
    }

    /**
    * Get archMonster
    *
    * @return Monster
    */
    public function getArchMonster()
    {
        return $this->archMonster;
    }

    /**
    * Set normalMonster
    *
    * @param Monster $normalMonster
    * @return Monster
    */
    public function setNormalMonster(Monster $normalMonster = null)
    {
        $this->normalMonster = $normalMonster;

        return $this;
    }

    /**
    * Get normalMonster
    *
    * @return Monster
    */
    public function getNormalMonster()
    {
        return $this->normalMonster;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
