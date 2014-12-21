<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

abstract class MergeableTagTemplate implements TagTemplateInterface
{
	private $domName;
	private $mergeable;

	public function __construct($domName, $mergeable = true)
	{
		$this->domName = $domName;
		$this->mergeable = $mergeable;
	}

	public function getDOMName()
	{
		return $this->domName;
	}
	public abstract function getCSSClass();
	public abstract function getCSSStyle();
	public function isMergeable()
	{
		return $this->mergeable;
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
		$myCSSClass = $this->getCSSClass();
		$myCSSStyle = $this->getCSSStyle();
		if ($this->mergeable &&
			$tag->getChildCount() == 1 &&
			($child = $tag->getChildAt(0)) instanceof Tag &&
			($tpl = $child->getTemplate()) instanceof self &&
			$tpl->getDOMName() == $this->domName &&
			$tpl->isMergeable()) {
			$elem = $child->toDOMNode($doc);
			if ($myCSSClass) {
				$cssClass = $doc->getAttribute('class');
				$doc->setAttribute('class', $myCSSClass . ($cssClass ? (' ' . $cssClass) : ''));
			}
			if ($myCSSStyle) {
				$cssStyle = $doc->getAttribute('style');
				$doc->setAttribute('style', $myCSSStyle . ($cssStyle ? (' ' . $cssStyle) : ''));
			}
			$this->mergeElement($tag, $elem);
			return $elem;
		} else {
			$elem = $doc->createElement($this->domName);
			if ($myCSSClass)
				$doc->setAttribute('class', $myCSSClass);
			if ($myCSSStyle)
				$doc->setAttribute('style', $myCSSStyle);
			$this->initializeElement($tag, $elem);
			$this->appendChildrenToDOM($tag, $elem);
			return $elem;
		}
	}

	protected function mergeElement(Tag $tag, \DOMElement $elem) { }
	protected function initializeElement(Tag $tag, \DOMElement $elem) { }
	protected function appendChildrenToDOM(Tag $tag, \DOMElement $elem)
	{
		$tag->appendChildrenToDOM($elem);
	}

	public function verifyParent(Tag $child, NodeInterface $parent) { }
	public function verifyChild(Tag $parent, NodeInterface $child) { }
}
