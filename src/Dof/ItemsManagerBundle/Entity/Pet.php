<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="lastEat", type="datetime")
     */
    private $lastEat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="afterMax", type="boolean")
     */
    private $afterMax;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raise", type="boolean")
     */
    private $raise;

    /**
     * @var string
     *
     * @ORM\Column(name="characteristic", type="string")
     */
    private $characteristic;

    /**
     * Set lastEat
     *
     * @param datetime $lastEat
     * @return Pet
     */
    public function setLastEat($lastEat)
    {
        $this->lastEat = $lastEat;

        return $this;
    }

    /**
     * Get lastEat
     *
     * @return datetime
     */
    public function getLastEat()
    {
        return $this->lastEat;
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
     * Set characteristic
     *
     * @param string $characteristic
     * @return Pet
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic
     *
     * @return string
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    public function isPet() { return true; }
    public function getClassId() { return 'pet'; }
}
