<?php

namespace Dof\Bundle\MapBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * Area.
 *
 * @ORM\Table(name="dof_map_areas")
 * @ORM\Entity(repositoryClass="AreaRepository")
 */
class Area implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="left_", type="integer")
     */
    private $left;

    /**
     * @var int
     *
     * @ORM\Column(name="top_", type="integer")
     */
    private $top;

    /**
     * @var int
     *
     * @ORM\Column(name="width_", type="integer")
     */
    private $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height_", type="integer")
     */
    private $height;

    public function __construct()
    {
        $this->subAreas = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Area
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set superArea.
     *
     * @param SuperArea $superArea
     *
     * @return Area
     */
    public function setSuperArea(SuperArea $superArea)
    {
        $this->superArea = $superArea;

        return $this;
    }

    /**
     * Get superArea.
     *
     * @return SuperArea
     */
    public function getSuperArea()
    {
        return $this->superArea;
    }

    /**
     * Add subAreas.
     *
     * @param SubArea $subAreas
     *
     * @return Area
     */
    public function addSubArea(SubArea $subAreas)
    {
        $this->subAreas[] = $subAreas;

        return $this;
    }

    /**
     * Remove subAreas.
     *
     * @param SubArea $subAreas
     *
     * @return Area
     */
    public function removeSubArea(SubArea $subAreas)
    {
        $this->subAreas->removeElement($subAreas);

        return $this;
    }

    /**
     * Get subAreas.
     *
     * @return Collection
     */
    public function getSubAreas()
    {
        return $this->subAreas;
    }

    /**
     * Set left.
     *
     * @param int $left
     *
     * @return Area
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Get left.
     *
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set top.
     *
     * @param int $top
     *
     * @return Area
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top.
     *
     * @return int
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set width.
     *
     * @param int $width
     *
     * @return Area
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height.
     *
     * @param int $height
     *
     * @return Area
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height.
     *
     * @return int
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
