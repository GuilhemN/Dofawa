<?php

namespace Dof\Bundle\MapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
# L'interface n'est pas utilisé afin que le __toString() soit utilisé et non le nom de l'entité
# use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use Dof\Bundle\MonsterBundle\Entity\Dungeon;

/**
 * MapPosition.
 *
 * @ORM\Table(name="dof_map_positions")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MapBundle\Entity\MapPositionRepository")
 */
class MapPosition implements IdentifiableInterface
{
    use TimestampableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="x", type="integer")
     */
    private $x;

    /**
     * @var int
     *
     * @ORM\Column(name="y", type="integer")
     */
    private $y;

    /**
     * @var bool
     *
     * @ORM\Column(name="outdoor", type="boolean")
     */
    private $outdoor;

    /**
     * @var int
     *
     * @ORM\Column(name="capabilities", type="integer")
     */
    private $capabilities;

    /**
     * @var int
     *
     * @ORM\Column(name="worldMap", type="integer")
     */
    private $worldMap;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasPriorityOnWorldMap", type="boolean")
     */
    private $hasPriorityOnWorldMap;

    /**
     * @var Monster
     *
     * @ORM\ManyToOne(targetEntity="SubArea", inversedBy="maps")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subArea;

    /**
     * @ORM\OneToMany(targetEntity="Dof\Bundle\MonsterBundle\Entity\Dungeon", mappedBy="entranceMap")
     */
    private $entranceDungeons;

    /**
     * @ORM\OneToMany(targetEntity="Dof\Bundle\MonsterBundle\Entity\Dungeon", mappedBy="exitMap")
     */
    private $exitDungeons;

    public function __construct()
    {
        $this->entranceDungeons = new ArrayCollection();
        $this->exitDungeons = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return MapPosition
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
     * Set x.
     *
     * @param int $x
     *
     * @return MapPosition
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x.
     *
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y.
     *
     * @param int $y
     *
     * @return MapPosition
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y.
     *
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set outdoor.
     *
     * @param bool $outdoor
     *
     * @return MapPosition
     */
    public function setOutdoor($outdoor)
    {
        $this->outdoor = $outdoor;

        return $this;
    }

    /**
     * Get outdoor.
     *
     * @return bool
     */
    public function getOutdoor()
    {
        return $this->outdoor;
    }

    /**
     * Get outdoor.
     *
     * @return bool
     */
    public function isOutdoor()
    {
        return $this->outdoor;
    }

    /**
     * Set capabilities.
     *
     * @param int $capabilities
     *
     * @return MapPosition
     */
    public function setCapabilities($capabilities)
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    /**
     * Get capabilities.
     *
     * @return int
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    /**
     * Set worldMap.
     *
     * @param int $worldMap
     *
     * @return MapPosition
     */
    public function setWorldMap($worldMap)
    {
        $this->worldMap = $worldMap;

        return $this;
    }

    /**
     * Get worldMap.
     *
     * @return int
     */
    public function getWorldMap()
    {
        return $this->worldMap;
    }

    /**
     * Set hasPriorityOnWorldMap.
     *
     * @param bool $hasPriorityOnWorldMap
     *
     * @return MapPosition
     */
    public function setHasPriorityOnWorldMap($hasPriorityOnWorldMap)
    {
        $this->hasPriorityOnWorldMap = $hasPriorityOnWorldMap;

        return $this;
    }

    /**
     * Get hasPriorityOnWorldMap.
     *
     * @return bool
     */
    public function getHasPriorityOnWorldMap()
    {
        return $this->hasPriorityOnWorldMap;
    }

    /**
     * Set subArea.
     *
     * @param SubArea $subArea
     *
     * @return MapPosition
     */
    public function setSubArea(SubArea $subArea = null)
    {
        $this->subArea = $subArea;

        return $this;
    }

    /**
     * Get subArea.
     *
     * @return SubArea
     */
    public function getSubArea()
    {
        return $this->subArea;
    }

    /**
     * Add entranceDungeon.
     *
     * @param Dungeon $entranceDungeon
     *
     * @return MapPosition
     */
    public function addEntranceDungeon(Dungeon $entranceDungeon)
    {
        $this->entranceDungeon[] = $entranceDungeon;

        return $this;
    }

    /**
     * Remove entranceDungeon.
     *
     * @param Dungeon $entranceDungeon
     *
     * @return MapPosition
     */
    public function removeEntranceDungeon(Dungeon $entranceDungeon)
    {
        $this->entranceDungeon->removeElement($entranceDungeon);

        return $this;
    }

    /**
     * Get entranceDungeon.
     *
     * @return Collection
     */
    public function getEntranceDungeon()
    {
        return $this->entranceDungeon;
    }

    /**
     * Add exitDungeon.
     *
     * @param Dungeon $exitDungeon
     *
     * @return MapPosition
     */
    public function addExitDungeon(Dungeon $exitDungeon)
    {
        $this->exitDungeon[] = $exitDungeon;

        return $this;
    }

    /**
     * Remove exitDungeon.
     *
     * @param Dungeon $exitDungeon
     *
     * @return MapPosition
     */
    public function removeExitDungeon(Dungeon $exitDungeon)
    {
        $this->exitDungeon->removeElement($exitDungeon);

        return $this;
    }

    /**
     * Get exitDungeon.
     *
     * @return Collection
     */
    public function getExitDungeon()
    {
        return $this->exitDungeon;
    }

    public function __toString()
    {
        return '['.$this->x.', '.$this->y.']';
    }
}
