<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;

/**
 * QuestObjectiveTemplateRelation.
 *
 * @ORM\Table(name="dof_quest_objective_type_relations")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\QuestObjectiveTemplateRelationRepository")
 */
class QuestObjectiveTemplateRelation implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var objectiveTemplate
     *
     * @ORM\ManyToOne(targetEntity="QuestObjectiveTemplate", inversedBy="relations", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $objectiveTemplate;

    /**
     * @var string
     *
     * @ORM\Column(name="targetEntity", type="string", length=255)
     */
    private $targetEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="column1", type="string", length=255)
     */
    private $column1;

    /**
     * @var string
     *
     * @ORM\Column(name="column2", type="string", length=255)
     */
    private $column2;

    /**
     * @var string
     *
     * @ORM\Column(name="column3", type="string", length=255)
     */
    private $column3;

    /**
     * @var string
     *
     * @ORM\Column(name="column4", type="string", length=255)
     */
    private $column4;

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
     * Set objectiveTemplate.
     *
     * @param QuestObjectiveTemplate $objectiveTemplate
     *
     * @return QuestObjectiveTemplateRelation
     */
    public function setObjectiveTemplate(QuestObjectiveTemplate $objectiveTemplate)
    {
        $this->objectiveTemplate = $objectiveTemplate;

        return $this;
    }

    /**
     * Get objectiveTemplate.
     *
     * @return QuestObjectiveTemplate
     */
    public function getObjectiveTemplate()
    {
        return $this->objectiveTemplate;
    }

    /**
     * Set targetEntity.
     *
     * @param string $targetEntity
     *
     * @return QuestObjectiveTemplateRelation
     */
    public function setTargetEntity($targetEntity)
    {
        $this->targetEntity = $targetEntity;

        return $this;
    }

    /**
     * Get targetEntity.
     *
     * @return string
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    /**
     * Set column1.
     *
     * @param string $column1
     *
     * @return QuestObjectiveTemplateRelation
     */
    public function setColumn1($column1)
    {
        $this->column1 = $column1;

        return $this;
    }

    /**
     * Get column1.
     *
     * @return string
     */
    public function getColumn1()
    {
        return $this->column1;
    }

    /**
     * Set column2.
     *
     * @param string $column2
     *
     * @return QuestObjectiveTemplateRelation
     */
    public function setColumn2($column2)
    {
        $this->column2 = $column2;

        return $this;
    }

    /**
     * Get column2.
     *
     * @return string
     */
    public function getColumn2()
    {
        return $this->column2;
    }

    /**
     * Set column3.
     *
     * @param string $column3
     *
     * @return QuestObjectiveTemplateRelation
     */
    public function setColumn3($column3)
    {
        $this->column3 = $column3;

        return $this;
    }

    /**
     * Get column3.
     *
     * @return string
     */
    public function getColumn3()
    {
        return $this->column3;
    }

    /**
     * Set column4.
     *
     * @param string $column4
     *
     * @return QuestObjectiveTemplateRelation
     */
    public function setColumn4($column4)
    {
        $this->column4 = $column4;

        return $this;
    }

    /**
     * Get column4.
     *
     * @return string
     */
    public function getColumn4()
    {
        return $this->column4;
    }
}
