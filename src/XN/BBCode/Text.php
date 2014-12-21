<?php
namespace XN\BBCode;

class Text extends Node
{
	private $value;

	public function __construct($value, $offset = 0)
	{
		parent::__construct($offset);
		$this->value = $value;
	}

	public function getName()
	{
		return '#text';
	}
	public function getValue()
	{
		return $this->value;
	}
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	public function toDOMNode(\DOMDocument $doc)
	{
		return $doc->createTextNode($this->value);
	}

	protected function verifyParent(NodeInterface $parent) { }
}
