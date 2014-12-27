<?php
namespace XN\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
* @Annotation
* @Target("CLASS")
* @Target("METHOD")
*/
final class Secure extends Annotation
{
}
