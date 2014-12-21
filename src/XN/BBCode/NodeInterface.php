<?php
namespace XN\BBCode;

interface NodeInterface
{
	public function getParent();
	public function setParent(NodeInterface $parent = null);

	public function getOffset();
	public function getName();
	public function getValue();

	public function toDOMNode(\DOMDocument $doc);
}
