<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

class SimpleTagTemplate implements TagTemplateInterface
{
	private $names;
	private $domName;
	private $cssClass;
	private $cssStyle;
	private $mergeable;

	public function __construct($names, $domName, $cssClass, $cssStyle, $mergeable = true)
	{
		$this->names = (array)$names;
		$this->domName = $domName;
		$this->cssClass = $cssClass;
		$this->cssStyle = $cssStyle;
		$this->mergeable = $mergeable;
	}

	public function getNames()
	{
		return $this->names;
	}
	public function getDOMName()
	{
		return $this->domName;
	}
	public function getCSSClass()
	{
		return $this->cssClass;
	}
	public function getCSSStyle()
	{
		return $this->cssStyle;
	}
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
		if ($this->mergeable &&
			$tag->getChildCount() == 1 &&
			($child = $tag->getChildAt(0)) instanceof Tag &&
			($tpl = $child->getTemplate()) instanceof self &&
			$tpl->domName == $this->domName &&
			$tpl->mergeable) {
			$elem = $child->toDOMNode($doc);
			if ($this->cssClass !== null) {
				$cssClass = $doc->getAttribute('class');
				$doc->setAttribute('class', $this->cssClass . ($cssClass ? (' ' . $cssClass) : ''));
			}
			if ($this->cssStyle !== null) {
				$cssStyle = $doc->getAttribute('style');
				$doc->setAttribute('style', $this->cssStyle . ($cssStyle ? (' ' . $cssStyle) : ''));
			}
			return $elem;
		} else {
			$elem = $doc->createElement($this->domName);
			if ($this->cssClass !== null)
				$doc->setAttribute('class', $this->cssClass);
			if ($this->cssStyle !== null)
				$doc->setAttribute('style', $this->cssStyle);
			$tag->appendChildrenToDOM($elem);
			return $elem;
		}
	}

	public function verifyParent(Tag $child, NodeInterface $parent) { }
	public function verifyChild(Tag $parent, NodeInterface $child) { }
}
