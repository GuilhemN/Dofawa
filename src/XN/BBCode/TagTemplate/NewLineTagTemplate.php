<?php
namespace XN\BBCode\TagTemplate;

use XN\BBCode\NodeInterface;
use XN\BBCode\Tag;
use XN\BBCode\TagTemplateInterface;

class NewLineTagTemplate implements TagTemplateInterface
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
		return [ 'br' ];
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
		return $doc->createElement('br');
	}

	public function verifyParent(Tag $child, NodeInterface $parent) { }
	public function verifyChild(Tag $parent, NodeInterface $child) { }
}
