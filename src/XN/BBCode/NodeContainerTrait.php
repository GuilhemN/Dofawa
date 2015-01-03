<?php
namespace XN\BBCode;

trait NodeContainerTrait
{
	private $children;

	protected function constructContainer()
	{
		$this->children = [ ];
	}
	protected function cloneContainer()
	{
		$children = $this->children;
		$this->children = [ ];
		foreach ($children as $child)
			$this->appendChild(clone $child);
	}

	public function hasChildren()
	{
		return !empty($this->children);
	}
	public function getChildren()
	{
		return $this->children;
	}
	public function getChildCount()
	{
		return count($this->children);
	}
	public function getChildAt($i)
	{
		if (!isset($this->children[$i]))
			throw new \Exception("Invalid child index");
		return $this->children[$i];
	}
	public function getChildIndex(Node $child)
	{
		return array_search($child, $this->children, true);
	}
	public function appendChild(Node $child)
	{
		$this->verifyChild($child);
		$parent = $child->getParent();
		$child->setParent($this);
		if ($parent)
			$parent->removeChild($child);
		$this->children[] = $child;
		return $this;
	}
	public function insertChildAt($idx, Node $child)
	{
		if ($idx < 0 || $idx > count($this->children))
			throw new \Exception("Invalid child index");
		$this->verifyChild($child);
		$parent = $child->getParent();
		$child->setParent($this);
		if ($parent)
			$parent->removeChild($child);
		array_splice($this->children, $idx, 0, [ $child ]);
		return $this;
	}
	public function removeChild(Node $child)
	{
		$idx = $this->getChildIndex($child);
		if ($idx !== false)
			$this->removeChildAt($idx);
		return $this;
	}
	public function removeChildAt($idx)
	{
		if (!isset($this->children[$i]))
			throw new \Exception("Invalid child index");
		$child = $this->children[$idx];
		array_splice($this->children, $idx, 1);
		if ($child->getParent() === $this)
			$child->setParent(null);
		return $this;
	}
	public function removeChildren()
	{
		$this->children = [ ];
	}

	public function appendChildrenToDOM(\DOMNode $node)
	{
		$doc = $node->ownerDocument;
		foreach ($this->children as $child){
			$result = $child->toDOMNode($doc);
			if($result !== null)
				$node->appendChild($result);
		}
		return $this;
	}

	protected abstract function verifyChild(NodeInterface $child);
}
