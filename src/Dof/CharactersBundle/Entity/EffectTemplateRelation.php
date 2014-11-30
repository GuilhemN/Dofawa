<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

/**
 * EffectTemplateRelation
 *
 * @ORM\Table(name="dof_effect_template_relations")
 * @ORM\Entity(repositoryClass="EffectTemplateRelationRepository")
 */
class EffectTemplateRelation implements IdentifiableInterface
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
	 * @var EffectTemplate
	 *
	 * @ORM\ManyToOne(targetEntity="EffectTemplate", inversedBy="relations", fetch="EAGER")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $effectTemplate;

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
     * @var boolean
     *
     * @ORM\Column(name="fragment", type="boolean")
     */
    private $fragment;

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
     * Set effectTemplate
     *
     * @param EffectTemplate $effectTemplate
     * @return EffectTemplateRelation
     */
    public function setEffectTemplate(EffectTemplate $effectTemplate)
    {
        $this->effectTemplate = $effectTemplate;

        return $this;
    }

    /**
     * Get effectTemplate
     *
     * @return EffectTemplate
     */
    public function getEffectTemplate()
    {
        return $this->effectTemplate;
    }

    /**
     * Set targetEntity
     *
     * @param string $targetEntity
     * @return EffectTemplateRelation
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
     * @return EffectTemplateRelation
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
     * @return EffectTemplateRelation
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
     * @return EffectTemplateRelation
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

    /**
     * Set fragment
     *
     * @param boolean $fragment
     * @return EffectTemplateRelation
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Get fragment
     *
     * @return boolean
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Get fragment
     *
     * @return boolean
     */
    public function isFragment()
    {
        return $this->fragment;
    }
}
