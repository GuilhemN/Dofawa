<?php

namespace XN\Grammar;

use Doctrine\Common\Annotations\Reader as AnnotationReader;

class ParserFactory
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function createParser($context)
    {
        $ctxcls = get_class($context);
        $parcls = 'XN\Grammar\Parser\P'.md5($ctxcls);
        if (!class_exists($parcls)) {
            $compiler = new ParserCompiler($this->annotationReader, $parcls);
            $compiler->compile(new \ReflectionClass($ctxcls));
            $compiler->load();
        }

        return new $parcls($context);
    }
}
