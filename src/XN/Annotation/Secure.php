<?php
namespace XN\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
* @Annotation
* @Target("METHOD")
*/
final class Secure extends Annotation
{
    public $role;
}
