<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

class ImageTagTemplate implements TagTemplateInterface
{
    public function __construct() { }

    public function getNames()
    {
        return ['img', 'image'];
    }

    public function isRaw()
    {
        return true;
    }
    public function isLeaf()
    {
        return false;
    }

    public function toDOMNode(Tag $tag, \DOMDocument $doc)
    {
        $src = '';
        foreach ($tag->getChildren() as $child)
            $src .= $child->getValue();

        $elem = $doc->createElement('img');
        $elem->setAttribute('src', $src);
        $elem->setAttribute('class', 'img-responsive');
        return $elem;
    }

    public function verifyParent(Tag $child, NodeInterface $parent) {
    }

    public function verifyChild(Tag $parent, NodeInterface $child) { }
}
