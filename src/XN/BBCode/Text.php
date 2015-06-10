<?php

namespace XN\BBCode;

class Text extends Node implements MergeableInterface
{
    private $value;

    public function __construct($value, $offset = 0)
    {
        parent::__construct($offset);
        $this->value = $value;
    }

    public function getName()
    {
        return '#text';
    }
    public function getValue()
    {
        return $this->value;
    }
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function toDOMNode(\DOMDocument $doc)
    {
        return $doc->createTextNode($this->value);
    }

    protected function verifyParent(NodeInterface $parent)
    {
    }

    public function canMerge(MergeableInterface $other)
    {
        return $other instanceof self;
    }
    public function inplaceMerge(MergeableInterface $other)
    {
        if (!$this->canMerge($other)) {
            throw new \Exception();
        }
        $this->value .= $other->value;

        return $this;
    }
    public function merge(MergeableInterface $other)
    {
        if (!$this->canMerge($other)) {
            throw new \Exception();
        }
        $clone = clone $this;
        $clone->value .= $other->value;

        return $clone;
    }
}
