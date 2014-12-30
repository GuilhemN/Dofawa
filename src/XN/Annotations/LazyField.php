<?php
namespace XN\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
* @Annotation
* @Target({"PROPERTY"})
*/
final class LazyField extends Annotation
{
    public $classMethod;
    public $valueMethod;

    public $setter;
}
