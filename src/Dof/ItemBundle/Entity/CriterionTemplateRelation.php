<?php

namespace Dof\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

/**
 * CriterionTemplateRelation
 *
 * @ORM\Table(name="dof_criterion_template_relations")
 * @ORM\Entity(repositoryClass="Dof\ItemBundle\Entity\CriterionTemplateRelationRepository")
 */
class CriterionTemplateRelation implements IdentifiableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @var criterionTemplate
    *
    * @ORM\ManyToOne(targetEntity="CriterionTemplate", inversedBy="relations", fetch="EAGER")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $criterionTemplate;

    /**
     * @var string
     *
     * @ORM\Column(name="target_entity", type="string", length=255)
     */
    private $targetEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="column1", type="string", length=255, nullable=true)
     */
    private $column1;

    /**
     * @var string
     *
     * @ORM\Column(name="column2", type="string", length=255, nullable=true)
     */
    private $column2;

    /**
    * @var string
    *
    * @ORM\Column(name="column3", type="string", length=255, nullable=true)
    */
    private $column3;

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
    * Set criterionTemplate
    *
    * @param CriterionTemplate $criterionTemplate
    * @return CriterionTemplateRelation
    */
    public function setCriterionTemplate(CriterionTemplate $criterionTemplate)
    {
        $this->criterionTemplate = $criterionTemplate;

        return $this;
    }

    /**
    * Get criterionTemplate
    *
    * @return CriterionTemplate
    */
    public function getCriterionTemplate()
    {
        return $this->criterionTemplate;
    }

    /**
     * Set targetEntity
     *
     * @param string $targetEntity
     * @return CriterionTemplateRelation
     */
    public function setTargetEntity($targetEntity)
    {
        $this->targetEntity = $targetEntity;

        return $this;
    }

    /**
     * Get targetEntity
     *
     * @return string
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    /**
     * Set column1
     *
     * @param string $column1
     * @return CriterionTemplateRelation
     */
    public function setColumn1($column1)
    {
        $this->column1 = $column1;

        return $this;
    }

    /**
     * Get column1
     *
     * @return string
     */
    public function getColumn1()
    {
        return $this->column1;
    }

    /**
     * Set column2
     *
     * @param string $column2
     * @return CriterionTemplateRelation
     */
    public function setColumn2($column2)
    {
        $this->column2 = $column2;

        return $this;
    }

    /**
     * Get column2
     *
     * @return string
     */
    public function getColumn2()
    {
        return $this->column2;
    }

    /**
    * Set column3
    *
    * @param string $column3
    * @return CriterionTemplateRelation
    */
    public function setColumn3($column3)
    {
        $this->column3 = $column3;

        return $this;
    }

    /**
    * Get column3
    *
    * @return string
    */
    public function getColumn3()
    {
        return $this->column3;
    }
}
