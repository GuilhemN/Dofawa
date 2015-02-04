<?php
namespace Dof\ItemBundle\Criteria;

use Dof\ItemBundle\Entity\CriterionTemplate;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Dof\Common\SnippetDescriptionTrait;

class SimpleCriterion extends Criterion
{
    use SnippetDescriptionTrait;

    private $characteristic;
    private $operator;
    private $params;

    private $criterionTemplate;
    private $visible;

    public function __construct($characteristic = null, $operator = null, array $params = array()){
        $this->characteristic = $characteristic;
        $this->operator = $operator;
        $this->params = $params;
        $this->visible = false;
    }

    public function setCharacteristic($characteristic) {
        $this->characteristic = $characteristic;
        return $this;
    }

    public function getCharacteristic() {
        return $this->characteristic;
    }

    public function setOperator($operator) {
        $this->operator = $operator;
        return $this;
    }

    public function getOperator() {
        return $this->operator;
    }

    public function setParams(array $params) {
        $this->params = $params;
        return $this;
    }

    public function getParams() {
        return $this->params;
    }

    public function setCriterionTemplate(CriterionTemplate $criterionTemplate){
        $this->criterionTemplate = $criterionTemplate;
        return $this;
    }

    public function getCriterionTemplate() {
        return $this->criterionTemplate;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
        return $this;
    }

    public function getVisible() {
        return $this->visible;
    }

    public function isStructureVisible() {
        return $this->getVisible();
    }

    public function setContainer(ContainerInterface $di) {
        $this->di = $di;
        return $this;
    }

    public function getContainer() {
        return $this->di;
    }

    public function getDescription($locale = 'fr')
    {
        $params = $this->params;
        array_unshift($params, $this->operator);
        $translator = $this->di->get('translator');
        $desc = $this->criterionTemplate->expandDescription($params, $locale);
        return $desc;
    }

    public function setParam1($param1) {
        $this->params[0] = $param1;
        return $this;
    }

    public function setParam2($param2) {
        $this->params[1] = $param2;
        return $this;
    }

    public function setParam3($param3) {
        $this->params[2] = $param3;
        return $this;
    }

    public function __toString() {
        return $this->getPlainTextDescription();
    }
}
