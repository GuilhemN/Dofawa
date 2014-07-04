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

use XN\DataBundle\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * Area
 *
 * @ORM\Table(name="dof_map_areas")
 * @ORM\Entity(repositoryClass="AreaRepository")
 */
class Area implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ReleaseBoundTrait, LocalizedNameTrait;

    /**
     * @var SuperArea
     *
     * @ORM\ManyToOne(targetEntity="SuperArea", inversedBy="areas")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $superArea;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="SubArea", mappedBy="area")
     */
    private $subAreas;

    /**
     * @var integer
     *
     * @ORM\Column(name="left", type="integer")
     */
    private $left;

    /**
     * @var integer
     *
     * @ORM\Column(name="top", type="integer")
     */
    private $top;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer")
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    public function __construct()
    {
        $this->subAreas = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Area
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
     * Set superArea
     *
     * @param SuperArea $superArea
     * @return Area
     */
    public function setSuperArea(SuperArea $superArea)
    {
        $this->superArea = $superArea;

        return $this;
    }

    /**
     * Get superArea
     *
     * @return SuperArea
     */
    public function getSuperArea()
    {
        return $this->superArea;
    }

    /**
     * Add subAreas
     *
     * @param SubArea $subAreas
     * @return Area
     */
    public function addSubArea(SubArea $subAreas)
    {
        $this->subAreas[] = $subAreas;

        return $this;
    }

    /**
     * Remove subAreas
     *
     * @param SubArea $subAreas
     * @return Area
     */
    public function removeSubArea(SubArea $subAreas)
    {
        $this->subAreas->removeElement($subAreas);

        return $this;
    }

    /**
     * Get subAreas
     *
     * @return Collection
     */
    public function getSubAreas()
    {
        return $this->subAreas;
    }

    /**
     * Set left
     *
     * @param integer $left
     * @return Area
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Get left
     *
     * @return integer
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set top
     *
     * @param integer $top
     * @return Area
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return integer
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Area
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Area
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
