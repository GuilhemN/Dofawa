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

    public function __construct(array $values) {
        if(empty($values['name']))
            throw new \LogicException('You must define a name for any action (annotation).');
        $this->name = $values['name'];
        $this->context = (array) $values['context'];
    }
}
