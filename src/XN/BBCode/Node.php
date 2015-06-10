<?php

namespace XN\BBCode;

abstract class Node implements NodeInterface
{
    private $parent;
    private $offset;

    public function __construct($offset = 0)
    {
        $this->offset = $offset;
    }
    public function __clone()
    {
        $this->parent = null;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getParent()
    {
        return $this->parent;
    }
    public function setParent(NodeInterface $parent = null)
    {
        if ($parent) {
            $this->verifyParent($parent);
        }
        $this->parent = $parent;

        return $this;
    }

    public function isContainer()
    {
        return false;
    }

    abstract protected function verifyParent(NodeInterface $parent);
}
