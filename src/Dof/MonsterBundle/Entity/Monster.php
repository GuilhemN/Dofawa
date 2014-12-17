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
    * @ORM\OneToMany(targetEntity="MonsterGrade", mappedBy="monster")
    * @ORM\JoinColumn(nullable=true)
    */
    private $grades;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
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
            if($min !== null or $min < $grade->getGrade()){
                $min = $grade->getGrade();
                $minGrade = $grade;
            }

        return $minGrade;
    }

    public function getMaxGrade(){
        $max = null; $maxGrade = null;
        foreach($this->grades as $grade)
            if($max !== null or $max < $grade->getGrade()){
                $max = $grade->getGrade();
                $maxGrade = $grade;
            }

        return $maxGrade;
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
