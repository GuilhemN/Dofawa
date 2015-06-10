<?php

namespace XN\BBCode;

class Tag extends Node
{
    use NodeContainerTrait;

    private $tpl;
    private $name;
    private $value;
    private $attrs;
    private $endValue;
    private $endAttrs;

    public function __construct(TagTemplateInterface $tpl, $name, $offset = 0)
    {
        parent::__construct($offset);
        $this->constructContainer();
        $this->tpl = $tpl;
        $this->name = $name;
        $this->value = null;
        $this->attrs = [];
        $this->endValue = null;
        $this->endAttrs = [];
    }
    public function __clone()
    {
        parent::__clone();
        $this->cloneContainer();
    }

    public function getTemplate()
    {
        return $this->tpl;
    }

    public function getName()
    {
        return $this->name;
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

    public function getAttributes()
    {
        return $this->attrs;
    }
    public function hasAttribute($key)
    {
        return isset($this->attrs[$key]);
    }
    public function getAttribute($key)
    {
        return isset($this->attrs[$key]) ? $this->attrs[$key] : null;
    }
    public function setAttribute($key, $value)
    {
        $this->attrs[$key] = $value;

        return $this;
    }
    public function removeAttribute($key)
    {
        unset($this->attrs[$key]);

        return $this;
    }
    public function removeAttributes()
    {
        $this->attrs = [];

        return $this;
    }

    public function getEndValue()
    {
        return $this->endValue;
    }
    public function setEndValue($endValue)
    {
        $this->endValue = $endValue;

        return $this;
    }

    public function getEndAttributes()
    {
        return $this->endAttrs;
    }
    public function hasEndAttribute($key)
    {
        return isset($this->endAttrs[$key]);
    }
    public function getEndAttribute($key)
    {
        return isset($this->endAttrs[$key]) ? $this->endAttrs[$key] : null;
    }
    public function setEndAttribute($key, $value)
    {
        $this->endAttrs[$key] = $value;

        return $this;
    }
    public function removeEndAttribute($key)
    {
        unset($this->endAttrs[$key]);

        return $this;
    }
    public function removeEndAttributes()
    {
        $this->endAttrs = [];

        return $this;
    }

    public function toDOMNode(\DOMDocument $doc)
    {
        return $this->tpl->toDOMNode($this, $doc);
    }

    protected function verifyParent(NodeInterface $parent)
    {
        return $this->tpl->verifyParent($this, $parent);
    }
    protected function verifyChild(NodeInterface $child)
    {
        return $this->tpl->verifyChild($this, $child);
    }
}
