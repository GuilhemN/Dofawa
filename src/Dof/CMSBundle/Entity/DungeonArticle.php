<?php

namespace Dof\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\MonsterBundle\Entity\Dungeon;

/**
 * DungeonArticle
 *
 * @ORM\Entity(repositoryClass="Dof\CMSBundle\Entity\DungeonArticleRepository")
 */
class DungeonArticle extends Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="roomsCount", type="integer")
     */
    private $roomsCount;

    /**
    * @var Dungeon
    *
    * @ORM\OneToOne(targetEntity="Dof\MonsterBundle\Entity\Dungeon", inversedBy="article")
    */
    private $dungeon;


    public function getName($locale = 'fr'){
        return !empty($this->dungeon) ? $this->dungeon->getName($locale) : call_user_func([$this, 'getName' . ucfirt($locale)]);
    }

    /**
     * Set roomsCount
     *
     * @param integer $roomsCount
     * @return DungeonArticle
     */
    public function setRoomsCount($roomsCount)
    {
        $this->roomsCount = $roomsCount;

        return $this;
    }

    /**
     * Get roomsCount
     *
     * @return integer
     */
    public function getRoomsCount()
    {
        return $this->roomsCount;
    }

    /**
    * Set dungeon
    *
    * @param Dungeon $dungeon
    * @return DungeonArticle
    */
    public function setDungeon(Dungeon $dungeon = null)
    {
        $this->dungeon = $dungeon;

        return $this;
    }

    /**
    * Get dungeon
    *
    * @return Dungeon
    */
    public function getDungeon()
    {
        return $this->dungeon;
    }

    public function isDungeonArticle() { return true; }
    public function getClass() { return 'dungeon'; }

    public function __toString() {
        return !empty($this->dungeon) ? $this->dungeon->getNameFr() : call_user_func([$this, 'getName' . ucfirt($locale)]);
    }
}
