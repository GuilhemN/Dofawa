<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

/**
 * QuestStep.
 *
 * @ORM\Table(name="dof_quest_steps")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\QuestStepRepository")
 */
class QuestStep implements IdentifiableInterface, LocalizedNameInterface
{
    use LocalizedNameTrait, LocalizedDescriptionTrait;

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
     * @ORM\Column(name="optimal_level", type="integer")
     */
    private $optimalLevel;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     */
    private $duration;

    /**
     * @var float
     *
     * @ORM\Column(name="kamas_ratio", type="float")
     */
    private $kamasRatio;

    /**
     * @var float
     *
     * @ORM\Column(name="xp_ratio", type="float")
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
     * @ORM\OneToMany(targetEntity="QuestObjective", mappedBy="step")
     * @ORM\JoinColumn(nullable=true)
     */
    private $objectives;

    /**
     * Set id.
     *
     * @return Quest
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
     * Set optimalLevel.
     *
     * @param int $optimalLevel
     *
     * @return QuestStep
     */
    public function setOptimalLevel($optimalLevel)
    {
        $this->optimalLevel = $optimalLevel;

        return $this;
    }

    /**
     * Get optimalLevel.
     *
     * @return int
     */
    public function getOptimalLevel()
    {
        return $this->optimalLevel;
    }

    /**
     * Set duration.
     *
     * @param float $duration
     *
     * @return QuestStep
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get float.
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set kamasRatio.
     *
     * @param float $kamasRatio
     *
     * @return QuestStep
     */
    public function setKamasRatio($kamasRatio)
    {
        $this->kamasRatio = $kamasRatio;

        return $this;
    }

    /**
     * Get kamasRatio.
     *
     * @return float
     */
    public function getKamasRatio()
    {
        return $this->kamasRatio;
    }

    /**
     * Set xpRatio.
     *
     * @param float $xpRatio
     *
     * @return QuestStep
     */
    public function setXpRatio($xpRatio)
    {
        $this->xpRatio = $xpRatio;

        return $this;
    }

    /**
     * Get xpRatio.
     *
     * @return float
     */
    public function getXpRatio()
    {
        return $this->xpRatio;
    }

    /**
     * Set quest.
     *
     * @param Quest $quest
     *
     * @return QuestStep
     */
    public function setQuest(Quest $quest)
    {
        $this->quest = $quest;

        return $this;
    }

    /**
     * Get quest.
     *
     * @return Quest
     */
    public function getQuest()
    {
        return $this->quest;
    }

    /**
     * Add objectives.
     *
     * @param QuestObjective $objectives
     *
     * @return QuestStep
     */
    public function addObjective(QuestObjective $objectives)
    {
        $this->objectives[] = $objectives;

        return $this;
    }

    /**
     * Remove objectives.
     *
     * @param QuestObjective $objectives
     *
     * @return QuestStep
     */
    public function removeObjective(QuestObjective $objectives)
    {
        $this->objectives->removeElement($objectives);

        return $this;
    }

    /**
     * Get objectives.
     *
     * @return Collection
     */
    public function getObjectives()
    {
        return $this->objectives;
    }
}
