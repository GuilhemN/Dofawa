<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use Dof\Bundle\MapBundle\Entity\MapPosition;
use Dof\Bundle\CMSBundle\Entity\DungeonArticle;

/**
 * Dungeon.
 *
 * @ORM\Table(name="dof_dungeons")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MonsterBundle\Entity\DungeonRepository")
 */
class Dungeon implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

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
     * @ORM\Column(name="optimalPlayerLevel", type="integer")
     */
    private $optimalPlayerLevel;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\MapBundle\Entity\MapPosition", inversedBy="entranceDungeons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entranceMap;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\MapBundle\Entity\MapPosition", inversedBy="exitDungeons")
     * @ORM\JoinColumn(nullable=true)
     */
    private $exitMap;

    /**
     * @var DungeonArticle
     *
     * @ORM\OneToOne(targetEntity="Dof\Bundle\CMSBundle\Entity\DungeonArticle", mappedBy="dungeon")
     */
    private $article;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Dungeon
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
     * Set optimalPlayerLevel.
     *
     * @param int $optimalPlayerLevel
     *
     * @return Dungeon
     */
    public function setOptimalPlayerLevel($optimalPlayerLevel)
    {
        $this->optimalPlayerLevel = $optimalPlayerLevel;

        return $this;
    }

    /**
     * Get optimalPlayerLevel.
     *
     * @return int
     */
    public function getOptimalPlayerLevel()
    {
        return $this->optimalPlayerLevel;
    }

    /**
     * Set entranceMap.
     *
     * @param MapPosition $entranceMap
     *
     * @return Dungeon
     */
    public function setEntranceMap(MapPosition $entranceMap)
    {
        $this->entranceMap = $entranceMap;

        return $this;
    }

    /**
     * Get entranceMap.
     *
     * @return MapPosition
     */
    public function getEntranceMap()
    {
        return $this->entranceMap;
    }

    /**
     * Set exitMap.
     *
     * @param MapPosition $exitMap
     *
     * @return Dungeon
     */
    public function setExitMap(MapPosition $exitMap = null)
    {
        $this->exitMap = $exitMap;

        return $this;
    }

    /**
     * Get exitMap.
     *
     * @return MapPosition
     */
    public function getExitMap()
    {
        return $this->exitMap;
    }

    /**
     * Set article.
     *
     * @param DungeonArticle $article
     *
     * @return Dungeon
     */
    public function setArticle(DungeonArticle $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article.
     *
     * @return DungeonArticle
     */
    public function getArticle()
    {
        return $this->article;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
