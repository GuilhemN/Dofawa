<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use XN\L10n\LocalizedDescriptionTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

use Dof\Common\GameTemplateString;

/**
 * EffectTemplate
 *
 * @ORM\Table(name="dof_effect_templates")
 * @ORM\Entity(repositoryClass="EffectTemplateRepository")
 */
class EffectTemplate implements IdentifiableInterface, TimestampableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

	use TimestampableTrait, ReleaseBoundTrait, LocalizedDescriptionTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="characteristic", type="integer")
     */
    private $characteristic;

    /**
     * @var integer
     *
     * @ORM\Column(name="element", type="integer", nullable=true)
     */
    private $element;

    /**
     * @var integer
     *
     * @ORM\Column(name="operator", type="integer", nullable=true)
     */
    private $operator;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="EffectTemplateRelation", mappedBy="effectTemplate", fetch="EAGER")
	 */
	private $relations;

	public function __construct()
	{
		$this->relations = new ArrayCollection();
	}

	/**
	 * Set id
	 *
	 * @param integer $id
	 * @return EffectTemplate
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
     * Set characteristic
     *
     * @param integer $characteristic
     * @return EffectTemplate
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic
     *
     * @return integer
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * Set element
     *
     * @param integer $element
     * @return EffectTemplate
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return integer
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set operator
     *
     * @param integer $operator
     * @return EffectTemplate
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return integer
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Add relations
     *
     * @param EffectTemplateRelation $relations
     * @return EffectTemplate
     */
    public function addRelation(EffectTemplateRelation $relations)
    {
        $this->relations[] = $relations;

        return $this;
    }

    /**
     * Remove relations
     *
     * @param EffectTemplateRelation $relations
     * @return EffectTemplate
     */
    public function removeRelation(EffectTemplateRelation $relations)
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
			return null;
		return GameTemplateString::expand($tpl, $context);
	}
}
