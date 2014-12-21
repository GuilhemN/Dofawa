<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

class ListTagTemplate implements TagTemplateInterface
{
	private $names;
	private $domName;
	private $cssClass;
	private $cssStyle;

	public function __construct($names, $domName, $cssClass, $cssStyle)
	{
		$this->names = (array)$names;
		$this->domName = $domName;
		$this->cssClass = $cssClass;
		$this->cssStyle = $cssStyle;
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
		$elem = $doc->createElement($this->domName);
		if ($this->cssClass !== null)
			$doc->setAttribute('class', $this->cssClass);
		if ($this->cssStyle !== null)
			$doc->setAttribute('style', $this->cssStyle);
		$curItem = null;
		foreach ($tag->getChildren() as $child) {
			if ($child instanceof Tag && $child->getTemplate() instanceof ItemTagTemplate) {
				$curItem = $doc->createElement('li');
				$elem->appendChild($curItem);
			} else {
				if (!$curItem) {
					$curItem = $doc->createElement('li');
					$elem->appendChild($curItem);
				}
				$curItem->appendChild($child->toDOMNode($doc));
			}
		}
		return $elem;
	}

	public function verifyParent(Tag $child, NodeInterface $parent) { }
	public function verifyChild(Tag $parent, NodeInterface $child) { }
}
