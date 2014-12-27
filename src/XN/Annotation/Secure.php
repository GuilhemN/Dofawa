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

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $values['role'] = $values['value'];
        }
        $this->role = $values['role'];
    }
}
