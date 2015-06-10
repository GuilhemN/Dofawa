<?php

namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;

class SimpleTagTemplate extends MergeableTagTemplate
{
    private $names;
    private $cssClass;
    private $cssStyle;

    public function __construct($names, $domName, $cssClass, $cssStyle, $mergeable = true)
    {
        parent::__construct($domName, $mergeable);
        $this->names = (array) $names;
        $this->cssClass = $cssClass;
        $this->cssStyle = $cssStyle;
    }

    public function getNames()
    {
        return $this->names;
    }
    public function getCSSClass()
    {
        return $this->cssClass;
    }
    public function getCSSStyle()
    {
        return $this->cssStyle;
    }

    public function verifyParent(Tag $child, NodeInterface $parent)
    {
    }
    public function verifyChild(Tag $parent, NodeInterface $child)
    {
    }
}
