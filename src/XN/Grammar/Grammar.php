<?php

namespace XN\Grammar;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Grammar extends Annotation
{
    public $ignore;
    public $synonyms;
}
