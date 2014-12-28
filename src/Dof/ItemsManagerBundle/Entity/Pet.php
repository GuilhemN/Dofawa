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
    * @ORM\Column(name="last_feeding", type="datetime")
    */
    protected $lastFeeding;
    /**
    * @var datetime
    *
    * @ORM\Column(name="next_feeding", type="datetime")
    */
    protected $nextFeeding;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raise", type="boolean")
     */
    protected $raise;

    /**
    * @var PetTemplate
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsBundle\Entity\PetTemplate")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    protected $mimibioteTemplate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="last_notification", type="datetime")
     */
    protected $lastNotification;

    /**
     * Set lastFeeding
     *
     * @param datetime $lastFeeding
     * @return Pet
     */
    public function setLastFeeding($lastFeeding)
    {
        $this->lastFeeding = $lastFeeding;
        $nextFeeding = clone $lastFeeding;
        $this->nextFeeding = $nextFeeding->modify('+' . $this->getItemTemplate()->getMinFeedInterval() . ' hour');

        return $this;
    }

    /**
     * Get lastFeeding
     *
     * @return datetime
     */
    public function getLastFeeding()
    {
        return $this->lastFeeding;
    }

    /**
    * Get nextMeal
    *
    * @return datetime
    */
    public function getNextFeeding()
    {
        return $this->nextFeeding;
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

    /**
     * Set lastNotification
     *
     * @param datetime $lastNotification
     * @return Pet
     */
    public function setLastNotification($lastNotification)
    {
        $this->lastNotification = $lastNotification;

        return $this;
    }

    /**
     * Get lastNotification
     *
     * @return datetime
     */
    public function getLastNotification()
    {
        return $this->lastNotification;
    }

    public function isPet() { return true; }
    public function getClassId() { return 'pet'; }
}
