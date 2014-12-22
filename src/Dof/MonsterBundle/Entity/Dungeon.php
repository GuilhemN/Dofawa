<?php

namespace Dof\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

use Dof\MapBundle\Entity\MapPosition;

/**
 * Dungeon
 *
 * @ORM\Table(name="dof_dungeons")
 * @ORM\Entity(repositoryClass="Dof\MonsterBundle\Entity\DungeonRepository")
 */
class Dungeon implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="optimalPlayerLevel", type="integer")
     */
    private $optimalPlayerLevel;

    /**
    * @ORM\OneToMany(targetEntity="Dof\MapBundle\Entity\MapPosition")
    * @ORM\JoinColumn(nullable=false)
    */
    private $entranceMap;

    /**
    * @ORM\OneToMany(targetEntity="Dof\MapBundle\Entity\MapPosition")
    * @ORM\JoinColumn(nullable=true)
    */
    private $exitMap;


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
     * Set optimalPlayerLevel
     *
     * @param integer $optimalPlayerLevel
     * @return Dungeon
     */
    public function setOptimalPlayerLevel($optimalPlayerLevel)
    {
        $this->optimalPlayerLevel = $optimalPlayerLevel;

        return $this;
    }

    /**
     * Get optimalPlayerLevel
     *
     * @return integer
     */
    public function getOptimalPlayerLevel()
    {
        return $this->optimalPlayerLevel;
    }

    /**
    * Set entranceMap
    *
    * @param MapPosition $entranceMap
    * @return Dungeon
    */
    public function setEntranceMap(MapPosition $entranceMap)
    {
        $this->entranceMap = $entranceMap;

        return $this;
    }

    /**
    * Get entranceMap
    *
    * @return MapPosition
    */
    public function getEntranceMap()
    {
        return $this->entranceMap;
    }

    /**
    * Set exitMap
    *
    * @param MapPosition $exitMap
    * @return Dungeon
    */
    public function setExitMap(MapPosition $exitMap)
    {
        $this->exitMap = $exitMap;

        return $this;
    }

    /**
    * Get exitMap
    *
    * @return MapPosition
    */
    public function getExitMap()
    {
        return $this->exitMap;
    }
}
