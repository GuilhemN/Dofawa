<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

class ItemTagTemplate implements TagTemplateInterface
{
	private static $instance;

	// Public for Symfony2 service instantiation
	// Avoid calling directly, prefer using the service, or getInstance
	public function __construct()
	{
		if (!self::$instance)
			self::$instance = $this;
	}

	public static function getInstance()
	{
		if (self::$instance)
			return self::$instance;
		return new self();
	}

	public function getNames()
	{
		return [ '*' ];
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
		throw new \Exception("Item tags don't have a direct translation in DOM");
	}

	public function verifyParent(Tag $child, NodeInterface $parent)
	{
		if (!($parent instanceof Tag && $parent->getTemplate() instanceof ListTagTemplate))
			throw new \Exception("Can't add item tags somewhere else than in list tags");
	}
	public function verifyChild(Tag $parent, NodeInterface $child)
	{
		throw new \Exception("Item tags can't have children");
	}
}
