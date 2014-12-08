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

    public function isPet() { return true; }
}
