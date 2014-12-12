<?php

namespace Dof\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

/**
 * QuestStep
 *
 * @ORM\Table(name="dof_quest_steps")
 * @ORM\Entity(repositoryClass="Dof\QuestBundle\Entity\QuestStepRepository")
 */
class QuestStep implements IdentifiableInterface, LocalizedNameInterface
{
    use LocalizedNameTrait, LocalizedDescriptionTrait;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="optimal_level", type="integer")
     */
    private $optimalLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="kamas_ratio", type="integer")
     */
    private $kamasRatio;

    /**
     * @var integer
     *
     * @ORM\Column(name="xp_ratio", type="integer")
     */
    private $xpRatio;

    /**
    * @var QuestCategory
    *
    * @ORM\ManyToOne(targetEntity="Quest", inversedBy="steps")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $quest;

    /**
    * Set id
    *
    * @return Quest
    */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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
     * Set optimalLevel
     *
     * @param integer $optimalLevel
     * @return QuestStep
     */
    public function setOptimalLevel($optimalLevel)
    {
        $this->optimalLevel = $optimalLevel;

        return $this;
    }

    /**
     * Get optimalLevel
     *
     * @return integer
     */
    public function getOptimalLevel()
    {
        return $this->optimalLevel;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return QuestStep
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set kamasRatio
     *
     * @param integer $kamasRatio
     * @return QuestStep
     */
    public function setKamasRatio($kamasRatio)
    {
        $this->kamasRatio = $kamasRatio;

        return $this;
    }

    /**
     * Get kamasRatio
     *
     * @return integer
     */
    public function getKamasRatio()
    {
        return $this->kamasRatio;
    }

    /**
     * Set xpRatio
     *
     * @param integer $xpRatio
     * @return QuestStep
     */
    public function setXpRatio($xpRatio)
    {
        $this->xpRatio = $xpRatio;

        return $this;
    }

    /**
     * Get xpRatio
     *
     * @return integer
     */
    public function getXpRatio()
    {
        return $this->xpRatio;
    }

    /**
    * Set quest
    *
    * @param Quest $quest
    * @return QuestStep
    */
    public function setQuest(Quest $quest)
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
}
