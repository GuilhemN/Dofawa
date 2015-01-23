<?php
namespace XN\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 * @Attributes({
 *   @Attribute("name", type = "string"),
 *   @Attribute("context",  type = "array"),
 * })
 */
final class Action extends Annotation
{
    public $name;
    public $context;
}
