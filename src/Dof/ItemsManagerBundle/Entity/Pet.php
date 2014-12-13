<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\Entity\PetTemplate;

/**
* Pet
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\PetRepository")
*/
class Pet extends Animal
{
    /**
     * @var datetime
     *
     * @ORM\Column(name="last_meal", type="datetime")
     */
    protected $lastMeal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="after_max", type="boolean")
     */
    protected $afterMax;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raise", type="boolean")
     */
    protected $raise;


    /**
    * @var Mount
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsBundle\Entity\PetTemplate")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    protected $mimibioteTemplate;

    /**
     * Set lastMeal
     *
     * @param datetime $lastMeal
     * @return Pet
     */
    public function setLastMeal($lastMeal)
    {
        $this->lastMeal = $lastMeal;

        return $this;
    }

    /**
     * Get lastMeal
     *
     * @return datetime
     */
    public function getLastMeal()
    {
        return $this->lastMeal;
    }

    /**
     * Set afterMax
     *
     * @param boolean $afterMax
     * @return Pet
     */
    public function setAfterMax($afterMax)
    {
        $this->afterMax = $afterMax;

        return $this;
    }

    /**
     * Get afterMax
     *
     * @return boolean
     */
    public function getAfterMax()
    {
        return $this->afterMax;
    }

    /**
     * Get afterMax
     *
     * @return boolean
     */
    public function isAfterMax()
    {
        return $this->afterMax;
    }

    /**
     * Set raise
     *
     * @param boolean $raise
     * @return Pet
     */
    public function setRaise($raise)
    {
        $this->raise = $raise;

        return $this;
    }

    /**
     * Get raise
     *
     * @return boolean
     */
    public function getRaise()
    {
        return $this->raise;
    }

    /**
     * Get raise
     *
     * @return boolean
     */
    public function isRaise()
    {
        return $this->raise;
    }

    
    /**
    * Set mimibioteTemplate
    *
    * @param Mount $mimibioteTemplate
    * @return SkinnedItem
    */
    public function setMimibioteTemplate(PetTemplate $mimibioteTemplate)
    {
        $this->mimibioteTemplate = $mimibioteTemplate;

        return $this;
    }

    /**
    * Get mimibioteTemplate
    *
    * @return mimibioteTemplate
    */
    public function getMimibioteTemplate()
    {
        return ($this->mimibioteTemplate !== null) ? $this->mimibioteTemplate : $this->itemTemplate;
    }

    public function isPet() { return true; }
    public function getClassId() { return 'pet'; }
}
