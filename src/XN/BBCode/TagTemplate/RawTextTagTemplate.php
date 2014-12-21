<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;
use XN\BBCode\Text;

class RawTextTagTemplate implements TagTemplateInterface
{
	private $names;

	public function __construct($names)
	{
		$this->names = (array)$names;
	}

	public function getNames()
	{
		return $this->names;
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
		$text = [ ];
		foreach ($tag->getChildren() as $child)
			$text[] = $child->getValue();
		return $doc->createTextNode(implode('', $text));
	}

	public function verifyParent(Tag $child, NodeInterface $parent) { }
	public function verifyChild(Tag $parent, NodeInterface $child)
	{
		if (!($child instanceof Text))
			throw new \Exception("Raw tags can only have text children");
	}
}
