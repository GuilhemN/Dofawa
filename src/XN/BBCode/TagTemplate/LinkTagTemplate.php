<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

class LinkTagTemplate implements TagTemplateInterface
{
    public function __construct() { }

    public function getNames()
    {
        return ['url', 'link'];
    }

    public function isRaw()
    {
        return false;
    }
    public function isLeaf()
    {
        return false;
    }

    public function toDOMNode(Tag $tag, \DOMDocument $doc)
    {
        if($tag->getValue() !== null)
            $href = $tag->getValue();
        else {
            $href = '';
            foreach ($tag->getChildren() as $child)
                $href .= $child->getValue();
        }
        $elem = $doc->createElement('a');
        $elem->setAttribute('href', $href);

        $this->initializeElement($tag, $elem);
        $this->appendChildrenToDOM($tag, $elem);
        return $elem;
    }

    public function verifyParent(Tag $child, NodeInterface $parent) {
        if($parent instanceof Tag && $parent->getTemplate() instanceof self)
            throw new \Exception("Link can't be a child of another link.");
    }

    public function verifyChild(Tag $parent, NodeInterface $child) { }
}
