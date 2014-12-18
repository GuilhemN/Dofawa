<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use XN\L10n\LocalizedDescriptionTrait;

use Dof\Common\GameTemplateString;

/**
 * Criterion
 *
 * @ORM\Table("dof_criterion_templates")
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\CriterionTemplateRepository")
 */
class CriterionTemplate implements IdentifiableInterface, TimestampableInterface
{
    use TimestampableTrait, LocalizedDescriptionTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="characteristic", type="string", length=2)
     */
    private $characteristic;

    /**
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=1, nullable=true)
     */
    private $operator;

    /**
    * @var Collection
    *
    * @ORM\OneToMany(targetEntity="CriterionTemplateRelation", mappedBy="criterion", fetch="EAGER")
    */
    private $relations;


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
     * Set characteristic
     *
     * @param string $characteristic
     * @return CriterionTemplate
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic
     *
     * @return string
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * Set operator
     *
     * @param string $operator
     * @return CriterionTemplate
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
    * Add relations
    *
    * @param CriterionTemplateRelation $relations
    * @return CriterionTemplate
    */
    public function addRelation(CriterionTemplateRelation $relations)
    {
        $this->relations[] = $relations;

        return $this;
    }

    /**
    * Remove relations
    *
    * @param CriterionTemplateRelation $relations
    * @return CriterionTemplate
    */
    public function removeRelation(CriterionTemplateRelation $relations)
    {
        $this->relations->removeElement($relations);

        return $this;
    }

    /**
    * Get relations
    *
    * @return Collection
    */
    public function getRelations()
    {
        return $this->relations;
    }

    public function expandDescription(array $context, $locale = 'fr')
    {
        $tpl = $this->getDescription($locale);
        if ($tpl === null)
            return [ ];
        $context = $context + [
            0 => null,
            1 => null,
            2 => null,
            3 => null
        ];
        return GameTemplateString::expand($tpl, $context);
    }
}
