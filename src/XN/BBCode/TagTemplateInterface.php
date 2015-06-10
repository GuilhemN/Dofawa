<?php

namespace XN\BBCode;

interface TagTemplateInterface
{
    public function getNames();

    public function isRaw();
    public function isLeaf();

    public function toDOMNode(Tag $tag, \DOMDocument $doc);

    public function verifyParent(Tag $child, NodeInterface $parent);
    public function verifyChild(Tag $parent, NodeInterface $child);
}
