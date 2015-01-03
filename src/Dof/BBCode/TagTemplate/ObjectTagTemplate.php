<?php
namespace Dof\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

use Symfony\Component\Templating\EngineInterface;
use Doctrine\Common\Persistence\ObjectManager;

use XN\Common\DOMUtils;

class ObjectTagTemplate implements TagTemplateInterface
{
    private $em;
    private $tp;

    public function __construct(ObjectManager $em, EngineInterface $tp) {
        $this->em = $em;
        $this->tp = $tp;
    }

    public function getNames()
    {
        return ['item', 'object'];
    }

    public function isRaw()
    {
        return false;
    }
    public function isLeaf()
    {
        return true;
    }

    public function toDOMNode(Tag $tag, \DOMDocument $doc)
    {
        if($tag->getValue() !== null)
            return;

        $item = $this->em->getRepository('DofItemsBundle:ItemTemplate')->findOneBySlug($tag->getValue());
        if($item === null)
            return;

        $view = $this->tp->render('DofItemsBundle::item.html.twig', ['item' => $item]);
        return DOMUtils::parseHTMLBodyFragment($view, $doc);
    }

    public function verifyParent(Tag $child, NodeInterface $parent) {
        if($parent instanceof Tag && $parent->getTemplate() instanceof self)
        throw new \Exception("Link can't be a child of another link.");
    }

    public function verifyChild(Tag $parent, NodeInterface $child) { }
}
