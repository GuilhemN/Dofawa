<?php
namespace XN\CodeGeneration;

class SymbolAllocator implements \Countable, \IteratorAggregate
{
	private $symbols;

	public function __construct()
	{
		$this->symbols = [ ];
	}

	public function count()
	{
		return count($this->symbols);
	}
	public function getIterator()
	{
		return new \ArrayIterator($this->symbols);
	}

	public function isUsed($symbol)
	{
		return isset($this->symbols[$symbol]);
	}

	public function reserve($symbol)
	{
		if (isset($this->symbols[$symbol]))
			throw new \Exception("Symbol already reserved");
		$this->symbols[$symbol] = true;
		return $symbol;
	}

	public function allocate($prefix)
	{
		for ($i = 1; isset($this->symbols[$prefix . $i]); ++$i)
			if ($i < 0)
				throw new \Exception("Symbol space exhausted");
		$this->symbols[$prefix . $i] = true;
		return $prefix . $i;
	}

	public function free($symbol, $silent = false)
	{
		if (!($silent | isset($this->symbols[$symbol])))
			throw new \Exception("Symbol already freed");
		unset($this->symbols[$symbol]);
		return null;
	}
}
