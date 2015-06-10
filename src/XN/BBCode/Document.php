<?php

namespace XN\BBCode;

class Document implements NodeInterface
{
    use NodeContainerTrait;

    public function __construct()
    {
        $this->constructContainer();
    }
    public function __clone()
    {
        $this->cloneContainer();
    }

    public function getParent()
    {
        return;
    }
    public function setParent(NodeInterface $parent = null)
    {
        if ($parent) {
            throw new \LogicException("A Document can't be a child");
        }
    }

    public function getOffset()
    {
        return 0;
    }
    public function getName()
    {
        return '#document';
    }
    public function getValue()
    {
        return;
    }

    public function toDOMNode(\DOMDocument $doc)
    {
        $frag = $doc->createDocumentFragment();
        $this->appendChildrenToDOM($frag);

        return $frag;
    }

    protected function verifyChild(NodeInterface $child)
    {
    }
}
