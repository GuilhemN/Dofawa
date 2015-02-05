<?php

namespace Dof\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\Bundle\QuestBundle\Entity\Quest;

/**
 * QuestArticle
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\CMSBundle\Entity\QuestArticleRepository")
 */
class QuestArticle extends Article
{
    /**
    * @var Dungeon
    *
    * @ORM\OneToOne(targetEntity="Dof\Bundle\QuestBundle\Entity\Quest", inversedBy="article")
    */
    private $quest;

    public function getName($locale = 'fr'){
        return !empty($this->quest) ? $this->quest->getName($locale) : parent::getName($locale);
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
        try {
            if(empty($this->quest))
                return $this->nameFr . '[' . $this->id . ']';
            return $this->quest->getNameFr();
        } catch (\Exception $e) {

        }
    }
}
