<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\L10n\LocalizedDescriptionTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use Dof\Common\GameTemplateString;
use Dof\Bundle\CharacterBundle\EffectTemplateExpressionLanguage;

/**
 * EffectTemplate.
 *
 * @ORM\Table(name="dof_effect_templates")
 * @ORM\Entity(repositoryClass="EffectTemplateRepository")
 */
class EffectTemplate implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, ReleaseBoundTrait, LocalizedDescriptionTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="characteristic", type="integer")
     */
    private $characteristic;

    /**
     * @var int
     *
     * @ORM\Column(name="element", type="integer", nullable=true)
     */
    private $element;

    /**
     * @var int
     *
     * @ORM\Column(name="operator", type="integer", nullable=true)
     */
    private $operator;

    /**
     * @var string
     *
     * @ORM\Column(name="param1_expression", type="string", nullable=true)
     */
    private $param1Expression;

    /**
     * @var callable
     */
    private $compiledParam1Expression;

    /**
     * @var string
     *
     * @ORM\Column(name="param2_expression", type="string", nullable=true)
     */
    private $param2Expression;

    /**
     * @var callable
     */
    private $compiledParam2Expression;

    /**
     * @var string
     *
     * @ORM\Column(name="param3_expression", type="string", nullable=true)
     */
    private $param3Expression;

    /**
     * @var callable
     */
    private $compiledParam3Expression;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="EffectTemplateRelation", mappedBy="effectTemplate", fetch="EAGER")
     */
    private $relations;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_fr", type="text", nullable=true)
     */
    private $personalizedDescriptionFr;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_en", type="text", nullable=true)
     */
    private $personalizedDescriptionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_de", type="text", nullable=true)
     */
    private $personalizedDescriptionDe;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_es", type="text", nullable=true)
     */
    private $personalizedDescriptionEs;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_it", type="text", nullable=true)
     */
    private $personalizedDescriptionIt;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_pt", type="text", nullable=true)
     */
    private $personalizedDescriptionPt;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_ja", type="text", nullable=true)
     */
    private $personalizedDescriptionJa;

    /**
     * @var string
     *
     * @ORM\Column(name="personalized_description_ru", type="text", nullable=true)
     */
    private $personalizedDescriptionRu;

    public function __construct()
    {
        $this->relations = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return EffectTemplate
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
     * Set characteristic.
     *
     * @param int $characteristic
     *
     * @return EffectTemplate
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic.
     *
     * @return int
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * Set element.
     *
     * @param int $element
     *
     * @return EffectTemplate
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element.
     *
     * @return int
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set operator.
     *
     * @param int $operator
     *
     * @return EffectTemplate
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator.
     *
     * @return int
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set param1Expression.
     *
     * @param string $param1Expression
     *
     * @return EffectTemplate
     */
    public function setParam1Expression($param1Expression)
    {
        $this->param1Expression = $param1Expression;
        $this->compiledParam1Expression = null;

        return $this;
    }

    /**
     * Get param1Expression.
     *
     * @return string
     */
    public function getParam1Expression()
    {
        return $this->param1Expression;
    }

    /**
     * Set param2Expression.
     *
     * @param string $param2Expression
     *
     * @return EffectTemplate
     */
    public function setParam2Expression($param2Expression)
    {
        $this->param2Expression = $param2Expression;
        $this->compiledParam2Expression = null;

        return $this;
    }

    /**
     * Get param2Expression.
     *
     * @return string
     */
    public function getParam2Expression()
    {
        return $this->param2Expression;
    }

    /**
     * Set param3Expression.
     *
     * @param string $param3Expression
     *
     * @return EffectTemplate
     */
    public function setParam3Expression($param3Expression)
    {
        $this->param3Expression = $param3Expression;
        $this->compiledParam3Expression = null;

        return $this;
    }

    /**
     * Get param3Expression.
     *
     * @return string
     */
    public function getParam3Expression()
    {
        return $this->param3Expression;
    }

    private function compileParamExpression($expression, &$compiledExpression, $defaultFunction)
    {
        if ($compiledExpression) {
            return;
        }
        $expression = trim(strval($expression));
        if (!$expression) {
            $compiledExpression = [__CLASS__, $defaultFunction];

            return;
        }
        switch ($expression) {
            case 'param1':
                $compiledExpression = [__CLASS__, 'selectParam1'];
                break;
            case 'param2':
                $compiledExpression = [__CLASS__, 'selectParam2'];
                break;
            case 'param3':
                $compiledExpression = [__CLASS__, 'selectParam3'];
                break;
            default:
                $el = new EffectTemplateExpressionLanguage();
                $compiledExpression = eval('return function ($param1, $param2, $param3) { return '.$el->compile($expression, ['param1', 'param2', 'param3']).'; };');
                break;
        }
    }
    private static function selectParam1($param1, $param2, $param3)
    {
        return $param1;
    }
    private static function selectParam2($param1, $param2, $param3)
    {
        return $param2;
    }
    private static function selectParam3($param1, $param2, $param3)
    {
        return $param3;
    }

    /**
     * Add relations.
     *
     * @param EffectTemplateRelation $relations
     *
     * @return EffectTemplate
     */
    public function addRelation(EffectTemplateRelation $relations)
    {
        $this->relations[] = $relations;

        return $this;
    }

    /**
     * Remove relations.
     *
     * @param EffectTemplateRelation $relations
     *
     * @return EffectTemplate
     */
    public function removeRelation(EffectTemplateRelation $relations)
    {
        $this->relations->removeElement($relations);

        return $this;
    }

    /**
     * Get relations.
     *
     * @return Collection
     */
    public function getRelations()
    {
        return $this->relations;
    }

    public function expandDescription(array $context, $locale = 'fr')
    {
        $tpl = ($this->getPersonalizedDescription($locale) !== null) ? $this->getPersonalizedDescription($locale) : $this->getDescription($locale);
        if ($tpl === null) {
            return [];
        }
        $ctx2 = $context + [
            1 => null,
            2 => null,
            3 => null,
        ];
        $this->compileParamExpression($this->param1Expression, $this->compiledParam1Expression, 'selectParam1');
        $this->compileParamExpression($this->param2Expression, $this->compiledParam2Expression, 'selectParam2');
        $this->compileParamExpression($this->param3Expression, $this->compiledParam3Expression, 'selectParam3');

        return GameTemplateString::expand($tpl, [
            1 => call_user_func($this->compiledParam1Expression, $ctx2[1], $ctx2[2], $ctx2[3]),
            2 => call_user_func($this->compiledParam2Expression, $ctx2[1], $ctx2[2], $ctx2[3]),
            3 => call_user_func($this->compiledParam3Expression, $ctx2[1], $ctx2[2], $ctx2[3]),
        ]);
    }
    /**
     * Set personalizedDescriptionFr.
     *
     * @param string $personalizedDescriptionFr
     *
     * @return object
     */
    public function setPersonalizedDescriptionFr($personalizedDescriptionFr)
    {
        $this->personalizedDescriptionFr = $personalizedDescriptionFr;

        return $this;
    }

    /**
     * Get personalizedDescriptionFr.
     *
     * @return string
     */
    public function getPersonalizedDescriptionFr()
    {
        return $this->personalizedDescriptionFr;
    }

    /**
     * Set personalizedDescriptionEn.
     *
     * @param string $personalizedDescriptionEn
     *
     * @return object
     */
    public function setPersonalizedDescriptionEn($personalizedDescriptionEn)
    {
        $this->personalizedDescriptionEn = $personalizedDescriptionEn;

        return $this;
    }

    /**
     * Get personalizedDescriptionEn.
     *
     * @return string
     */
    public function getPersonalizedDescriptionEn()
    {
        return $this->personalizedDescriptionEn;
    }

    /**
     * Set personalizedDescriptionDe.
     *
     * @param string $personalizedDescriptionDe
     *
     * @return object
     */
    public function setPersonalizedDescriptionDe($personalizedDescriptionDe)
    {
        $this->personalizedDescriptionDe = $personalizedDescriptionDe;

        return $this;
    }

    /**
     * Get personalizedDescriptionDe.
     *
     * @return string
     */
    public function getPersonalizedDescriptionDe()
    {
        return $this->personalizedDescriptionDe;
    }

    /**
     * Set personalizedDescriptionEs.
     *
     * @param string $personalizedDescriptionEs
     *
     * @return object
     */
    public function setPersonalizedDescriptionEs($personalizedDescriptionEs)
    {
        $this->personalizedDescriptionEs = $personalizedDescriptionEs;

        return $this;
    }

    /**
     * Get personalizedDescriptionEs.
     *
     * @return string
     */
    public function getPersonalizedDescriptionEs()
    {
        return $this->personalizedDescriptionEs;
    }

    /**
     * Set personalizedDescriptionIt.
     *
     * @param string $personalizedDescriptionIt
     *
     * @return object
     */
    public function setPersonalizedDescriptionIt($personalizedDescriptionIt)
    {
        $this->personalizedDescriptionIt = $personalizedDescriptionIt;

        return $this;
    }

    /**
     * Get personalizedDescriptionIt.
     *
     * @return string
     */
    public function getPersonalizedDescriptionIt()
    {
        return $this->personalizedDescriptionIt;
    }

    /**
     * Set personalizedDescriptionPt.
     *
     * @param string $personalizedDescriptionPt
     *
     * @return object
     */
    public function setPersonalizedDescriptionPt($personalizedDescriptionPt)
    {
        $this->personalizedDescriptionPt = $personalizedDescriptionPt;

        return $this;
    }

    /**
     * Get personalizedDescriptionPt.
     *
     * @return string
     */
    public function getPersonalizedDescriptionPt()
    {
        return $this->personalizedDescriptionPt;
    }

    /**
     * Set personalizedDescriptionJa.
     *
     * @param string $personalizedDescriptionJa
     *
     * @return object
     */
    public function setPersonalizedDescriptionJa($personalizedDescriptionJa)
    {
        $this->personalizedDescriptionJa = $personalizedDescriptionJa;

        return $this;
    }

    /**
     * Get personalizedDescriptionJa.
     *
     * @return string
     */
    public function getPersonalizedDescriptionJa()
    {
        return $this->personalizedDescriptionJa;
    }

    /**
     * Set personalizedDescriptionRu.
     *
     * @param string $personalizedDescriptionRu
     *
     * @return object
     */
    public function setPersonalizedDescriptionRu($personalizedDescriptionRu)
    {
        $this->personalizedDescriptionRu = $personalizedDescriptionRu;

        return $this;
    }

    /**
     * Get personalizedDescriptionRu.
     *
     * @return string
     */
    public function getPersonalizedDescriptionRu()
    {
        return $this->personalizedDescriptionRu;
    }

    /**
     * Set personalizedDescription.
     *
     * @param string $personalizedDescription
     * @param string $locale
     *
     * @return object
     */
    public function setPersonalizedDescription($personalizedDescription, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->personalizedDescriptionFr = $personalizedDescription; break;
            case 'en': $this->personalizedDescriptionEn = $personalizedDescription; break;
            case 'de': $this->personalizedDescriptionDe = $personalizedDescription; break;
            case 'es': $this->personalizedDescriptionEs = $personalizedDescription; break;
            case 'it': $this->personalizedDescriptionIt = $personalizedDescription; break;
            case 'pt': $this->personalizedDescriptionPt = $personalizedDescription; break;
            case 'ja': $this->personalizedDescriptionJa = $personalizedDescription; break;
            case 'ru': $this->personalizedDescriptionRu = $personalizedDescription; break;
        }

        return $this;
    }

    /**
     * Get personalizedDescription.
     *
     * @param string|array $locale
     *
     * @return string
     */
    public function getPersonalizedDescription($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $personalizedDescription = $this->getPersonalizedDescription($loc);
                if ($personalizedDescription !== null) {
                    return $personalizedDescription;
                }
            }

            return;
        }
        switch ($locale) {
            case 'fr': return $this->personalizedDescriptionFr;
            case 'en': return $this->personalizedDescriptionEn;
            case 'de': return $this->personalizedDescriptionDe;
            case 'es': return $this->personalizedDescriptionEs;
            case 'it': return $this->personalizedDescriptionIt;
            case 'pt': return $this->personalizedDescriptionPt;
            case 'ja': return $this->personalizedDescriptionJa;
            case 'ru': return $this->personalizedDescriptionRu;
            default: return;
        }
    }
}
