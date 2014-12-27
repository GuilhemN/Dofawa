<?php
namespace XN\UtilityBundle;

class ParameterBag
{
    private $values;

    public function __construct(array $data = array()){
        $this->values = $data;
    }

    public function getParameter($param) {
        return $this->values[$param];
    }

    public function setParameter($param, $value) {
        $this->values[$param] = $value;
        return $this;
    }

    public function hasParameter($param) {
        return isset($this->values[$param]);
    }
}
