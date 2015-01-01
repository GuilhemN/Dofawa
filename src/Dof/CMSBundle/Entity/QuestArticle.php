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
    * @ORM\OneToOne(targetEntity="Dof\QuestBundle\Entity\Quest", inversedBy="article")
    */
    private $quest;

    public function getName($locale = 'fr'){
        return !empty($this->quest) ? $this->quest->getName($locale) : $this->getName($locale);
    }

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

    public function isQuestArticle() { return true; }
    public function getClass() { return 'quest'; }

    public function __toString() {
        return !empty($this->quest) ? $this->quest->getNameFr() : $this->getNameFr();
    }
}
