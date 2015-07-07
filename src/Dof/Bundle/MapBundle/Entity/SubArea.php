<?php

namespace Dof\Bundle\MapBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use Dof\Bundle\MonsterBundle\Entity\Monster;

/**
 * SubArea.
 *
 * @ORM\Table(name="dof_map_subareas")
 * @ORM\Entity(repositoryClass="SubAreaRepository")
 */
class SubArea implements IdentifiableInterface, LocalizedNameInterface
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
     * @var Area
     *
     * @ORM\ManyToOne(targetEntity="Area", inversedBy="subAreas")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $area;

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

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="MapPosition", mappedBy="subArea")
     */
    private $maps;

    /**
     * @ORM\ManyToMany(targetEntity="Dof\Bundle\MonsterBundle\Entity\Monster", inversedBy="subAreas")
     * @ORM\JoinTable(name="dof_monsters_sub_areas")
     */
    private $monsters;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->maps = new ArrayCollection();
        $this->monsters = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return SubArea
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
     * Set area.
     *
     * @param Area $area
     *
     * @return SubArea
     */
    public function setArea(Area $area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area.
     *
     * @return Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set left.
     *
     * @param int $left
     *
     * @return SubArea
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
     * @return SubArea
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
     * @return SubArea
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
     * @return SubArea
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

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return SubArea
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return SubArea
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Add maps.
     *
     * @param MapPosition $maps
     *
     * @return SubArea
     */
    public function addMap(MapPosition $maps)
    {
        $this->maps[] = $maps;

        return $this;
    }

    /**
     * Remove maps.
     *
     * @param MapPosition $maps
     *
     * @return SubArea
     */
    public function removeMap(MapPosition $maps)
    {
        $this->maps->removeElement($maps);

        return $this;
    }

    /**
     * Get maps.
     *
     * @return Collection
     */
    public function getMaps()
    {
        return $this->maps;
    }

    /**
     * Add monsters.
     *
     * @param Monster $monsters
     *
     * @return SubArea
     */
    public function addMonster(Monster $monsters)
    {
        $this->monsters[] = $monsters;

        return $this;
    }

    /**
     * Remove monsters.
     *
     * @param Monster $monsters
     *
     * @return SubArea
     */
    public function removeMonster(Monster $monsters)
    {
        $this->monsters->removeElement($monsters);

        return $this;
    }

    /**
     * Get monsters.
     *
     * @return Collection
     */
    public function getMonsters()
    {
        return $this->monsters;
    }

    public function __toString()
    {
        return $this->name;
    }
}
