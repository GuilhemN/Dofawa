<?php
namespace Dof\ItemsBundle\Criteria;

class SimpleCriterion extends Criterion
{
    private $characteristic;
    private $operator;
    private $params;

    public function __contruct($characteristic = null, $operator = null, $params = array()){
        $this->characteristic = $characteristic;
        $this->operator = $operator;
        $this->params = $params;
    }

    public function setCharacteristic($characteristic) {
        $this->characteristic = $characteristic;
        return $this;
    }

    public function getCharacteristicd() {
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
}
