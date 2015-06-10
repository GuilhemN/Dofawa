<?php

namespace XN\Grammar;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Repeated extends Annotation
{
    public $min;
    public $max;
}
