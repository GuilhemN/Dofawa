<?php

namespace Dof\MapBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;
use XN\DataBundle\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

use XN\DataBundle\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * SuperArea
 *
 * @ORM\Table(name="dof_map_superareas")
 * @ORM\Entity(repositoryClass="SuperAreaRepository")
 */
class SuperArea implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait, ReleaseBoundTrait, LocalizedNameTrait;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Area", mappedBy="superArea")
     */
    private $areas;

    public function __construct()
    {
        $this->areas = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return SuperArea
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
     * Add areas
     *
     * @param Area $areas
     * @return SuperArea
     */
    public function addArea(Area $areas)
    {
        $this->areas[] = $areas;

        return $this;
    }

    /**
     * Remove areas
     *
     * @param Area $areas
     * @return SuperArea
     */
    public function removeArea(Area $areas)
    {
        $this->areas->removeElement($areas);

        return $this;
    }

    /**
     * Get areas
     *
     * @return Collection
     */
    public function getAreas()
    {
        return $this->areas;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
