<?php

namespace Dof\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dof\Bundle\MonsterBundle\Entity\Dungeon;

/**
 * DungeonArticle.
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\CMSBundle\Entity\DungeonArticleRepository")
 */
class DungeonArticle extends Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="roomsCount", type="integer")
     */
    private $roomsCount;

    /**
     * @var Dungeon
     *
     * @ORM\OneToOne(targetEntity="Dof\Bundle\MonsterBundle\Entity\Dungeon", inversedBy="article")
     */
    private $dungeon;

    public function getName($locale = 'fr')
    {
        return !empty($this->dungeon) ? $this->dungeon->getName($locale) : parent::getName($locale);
    }

    /**
     * Set roomsCount.
     *
     * @param int $roomsCount
     *
     * @return DungeonArticle
     */
    public function setRoomsCount($roomsCount)
    {
        $this->roomsCount = $roomsCount;

        return $this;
    }

    /**
     * Get roomsCount.
     *
     * @return int
     */
    public function getRoomsCount()
    {
        return $this->roomsCount;
    }

    /**
     * Set dungeon.
     *
     * @param Dungeon $dungeon
     *
     * @return DungeonArticle
     */
    public function setDungeon(Dungeon $dungeon = null)
    {
        $this->dungeon = $dungeon;

        return $this;
    }

    /**
     * Get dungeon.
     *
     * @return Dungeon
     */
    public function getDungeon()
    {
        return $this->dungeon;
    }

    public function isDungeonArticle()
    {
        return true;
    }
    public function getClass()
    {
        return 'dungeon';
    }

    public function __toString()
    {
        return !empty($this->dungeon) ? $this->dungeon->getNameFr() : $this->getNameFr();
    }
}
