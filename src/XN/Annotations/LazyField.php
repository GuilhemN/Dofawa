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

    public function getClassMethod() {
        return !empty($this->classProperty) ? 'get' . ucfirst($this->classProperty) : 'getClass';
    }

    public function getValueMethod() {
        return !empty($this->valueProperty) ? 'get' . ucfirst($this->valueProperty) : 'getClassId';
    }
}
