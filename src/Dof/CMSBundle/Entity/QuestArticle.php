<?php

namespace Dof\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\QuestBundle\Entity\Quest;

/**
 * QuestArticle
 *
 * @ORM\Entity(repositoryClass="Dof\CMSBundle\Entity\QuestArticleRepository")
 */
class QuestArticle extends Article
{
    /**
    * @var Dungeon
    *
    * @ORM\OneToOne(targetEntity="Dof\QuestBundle\Entity\Quest")
    */
    private $quest;


    /**
    * Set quest
    *
    * @param Quest $quest
    * @return QuestArticle
    */
    public function setQuest(Quest $quest = null)
    {
        $this->quest = $quest;

        return $this;
    }

    /**
    * Get quest
    *
    * @return Quest
    */
    public function getQuest()
    {
        return $this->quest;
    }

    public function isQuest() { return true; }
    public function getClass() { return 'quest'; }
}
