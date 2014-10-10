<?php
namespace XN\Grammar;

use Doctrine\Common\Annotations\Reader as AnnotationReader;

use XN\CodeGeneration\PHPEmitter;
use XN\CodeGeneration\SymbolAllocator;

class ParserCompiler extends PHPEmitter
{
	const NS = 'XN\Grammar\\';

	private $ar;
	private $ns;
	private $cls;

	public function __construct(AnnotationReader $annotationReader, $parserClassName)
	{
		$this->ar = $annotationReader;
		$pos = strrpos($parserClassName, '\\');
		if ($pos === false) {
			$this->ns = null;
			$this->cls = $parserClassName;
		} else {
			$this->ns = substr($parserClassName, 0, $pos);
			$this->cls = substr($parserClassName, $pos + 1);
		}
	}

	public function compile(\ReflectionClass $contextClass)
	{
		$grammar = $this->ar->getClassAnnotation($contextClass, self::NS . 'Grammar');
		if ($grammar === null)
			throw new \InvalidArgumentException();
		$this->emitln('<?php');
		if ($this->ns)
			$this->emitln('namespace ' . $this->ns . ';');
		$this
			->emitln()
			->emitln('use XN\Grammar\Reader;')
			->emitln('use XN\Grammar\Parser;')
			->emitln()
			->emitln('class ' . $this->cls)
			->emitln('{')
			->indent()
			->emitln('private $context;')
			->emitln()
			->emitln('public function __construct(\\' . $contextClass->getName() . ' $context)')
			->emitln('{')
			->indent()
			->emitln('$this->context = $context;')
			->outdent()
			->emitln('}');
		if ($grammar->ignore)
			$this
				->emitln()
				->emitln('public function _ignore(Reader $reader)')
				->emitln('{')
				->indent()
				->emit('while ($reader->eatRegex(')
				->emitValue($grammar->ignore)
				->emitln(') !== null) { }')
				->outdent()
				->emitln('}');
		foreach ($contextClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $meth) {
			if (substr($meth->getName(), 0, 1) != '_') {
				$expr = $this->ar->getMethodAnnotation($meth, self::NS . 'Expression');
				if ($expr !== null)
					$this->compileMethod($meth->getName(), $expr, $grammar);
			}
		}
		if ($grammar->synonyms !== null)
			foreach ($grammar->synonyms as $name => $expr)
				$this->compileSynonym($name, $expr, $grammar);
		$this
			->outdent()
			->emitln('}');
	}

	public function compileMethod($name, $expr, Grammar $grammar)
	{
		$this
			->emitln()
			->emitln('public function ' . $name . '(Reader $reader, &$result)')
			->emitln('{')
			->indent();
		$argc = $this->compileRoot($expr, $grammar);
		switch ($argc) {
			case 0:
				$this->emitln('$result = $this->context->' . $name . '();');
				break;
			default:
				$this->emitln('$result = $this->context->' . $name . '($arg' . implode(', $arg', range(1, $argc)) . ');');
				break;
		}
		$this
			->emitln('return true;');
		$this
			->outdent()
			->emitln('}');
	}
	public function compileSynonym($name, $expr, Grammar $grammar)
	{
		$this
			->emitln()
			->emitln('public function ' . $name . '(Reader $reader, &$result)')
			->emitln('{')
			->indent();
		$argc = $this->compileRoot($expr, $grammar);
		switch ($argc) {
			case 0:
				$this->emitln('$result = null;');
				break;
			case 1:
				$this->emitln('$result = $arg1;');
				break;
			default:
				$this->emitln('$result = [ $arg' . implode(', $arg', range(1, $argc)) . ' ];');
				break;
		}
		$this
			->emitln('return true;');
		$this
			->outdent()
			->emitln('}');
	}

	private function compileRoot($expr, Grammar $grammar)
	{
		$localloc = new SymbolAllocator();
		$localloc->reserve('this');
		$localloc->reserve('reader');
		$localloc->reserve('result');
		$state = $localloc->allocate('state');
		$this->emitln('$' . $state . ' = $reader->getState();');
		if ($grammar->ignore)
			$this->emitln('$this->_ignore($reader);');
		$argCount = 0;
		$this->compileNode($expr, $grammar, $localloc, 0, false, -1, -1, $argCount);
		return $argCount;
	}

	private function emitExitStatement($stmtID, $value)
	{
		if ($stmtID === -1)
			return $this->emit('return ')->emitValue($value)->emitln(';');
		if ($stmtID === 1)
			return $this->emitln('break;');
		if ($stmtID > 1)
			return $this->emitln('break ' . $stmtID . ';');
	}
	private function emitCondition($condition, $trueStmtID, $falseStmtID)
	{
		if ($trueStmtID === null && $falseStmtID === null) {
			$condition();
			$this->emitln(';');
		}
	}

	private function compileNode($node, Grammar $grammar, SymbolAllocator $localloc, $arrayRank, $forceCapture, $trueStmtID, $falseStmtID, &$argCount)
	{
		if (is_string($node)) {
			$this->emit('$reader->eat(')->emitValue($node)->emitln(';');
		} elseif ($node instanceof Any) {
			$value = (array)$node->value;
			$allStrings = true;
			foreach ($value as $v) {
				if (!is_string($v)) {
					$allStrings = false;
					break;
				}
			}
			if ($allStrings)
				$this->emit('$reader->eatAny(')->emitValue($value)->emitln(';');
		} elseif ($node instanceof Optional) {
			$value = (array)$node->value;
		} elseif ($node instanceof Regex) {
		} elseif ($node instanceof Repeated) {
		} elseif ($node instanceof Sequence || $node instanceof Expression) {
		} elseif ($node instanceof SubExpression) {
		} else
			throw new \LogicException("Unrecognized node type : " . (is_object($node) ? get_class($node) : gettype($node)));
	}
}
