<?php
namespace XN\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
* @Annotation
* @Target({"PROPERTY"})
*/
final class LazyField extends Annotation
{
    public $classProperty;
    public $valueProperty;

    public function getClassProperty() {
        return !empty($this->classProperty) ? $this->classProperty : 'class';
    }
    public function getValueProperty() {
        return !empty($this->valueProperty) ? $this->valueProperty : 'classId';
    }

    public function getClassMethod() {
        return 'get' . ucfirst($this->getClassProperty());
    }

    public function getValueMethod() {
        return 'get' . ucfirst($this->getValueProperty());
    }

    public function getSetterClassMethod() {
        return 'set' . ucfirst($this->getClassProperty());
    }

    public function getSetterValueMethod() {
        return 'set' . ucfirst($this->getValueProperty());
    }
}
