<?php
namespace XN\CodeGeneration;

class FunctionBuilder
{
	const START_LABEL = 0;

	private $locals;
	private $labels;
	private $targets;
	private $ops;
	private $stackH;

	public function __construct($args)
	{
		$this->locals = new SymbolAllocator();
		$this->labels = [ [ 0, 0, 0, 0 ] ];
		$this->targets = [ true ];
		$this->ops = [ ];
		$this->stackH = 0;
		$this->locals->reserve('this');
		$this->locals->reserve('GLOBALS');
		$this->locals->reserve('_ENV');
		$this->locals->reserve('_GET');
		$this->locals->reserve('_POST');
		$this->locals->reserve('_FILES');
		$this->locals->reserve('_COOKIE');
		$this->locals->reserve('_SERVER');
	}

	public function getLocals()
	{
		return $this->locals;
	}
	public function countLocals()
	{
		return count($this->locals);
	}
	public function isLocalUsed($local)
	{
		return $this->locals->isUsed($local);
	}
	public function reserveLocal($local)
	{
		return $this->locals->reserve($local);
	}
	public function allocateLocal($prefix)
	{
		return $this->locals->allocate($prefix);
	}
	public function freeLocal($local, $silent = false)
	{
		return $this->locals->free($local, $silent);
	}

	public function allocateLabel()
	{
		$labelId = count($this->labels);
		$this->labels[$labelId] = [ null, null, 0, 0 ];
		return $labelId;
	}
	public function markLabel($labelId)
	{
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		if ($this->labels[$labelId][0] !== null)
			throw new \LogicException("Label already marked");
		$this->setLabelStackH($labelId, $this->stackH);
		$this->labels[$labelId][0] = count($this->ops);
		return $this;
	}
	public function getLabelStackHeight($labelId)
	{
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		return $this->labels[$labelId][1];
	}
	public function isLabelMarked($labelId)
	{
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		return $this->labels[$labelId][0] !== null;
	}
	public function getForwardBranchCount($labelId)
	{
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		return $this->labels[$labelId][2];
	}
	public function getBackwardBranchCount($labelId)
	{
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		return $this->labels[$labelId][3];
	}
	
	private function setLabelStackH($labelId, &$stackH)
	{
		if ($this->labels[$labelId][1] === null) {
			if ($stackH === null)
				throw new \LogicException("Labels which can be reached only by backward branching are not supported");
			$this->labels[$labelId][1] = $stackH;
		} elseif ($this->labels[$labelId][1] !== $stackH) {
			if ($stackH !== null)
				throw new \LogicException("Non-constant stack height at label");
			$stackH = $this->labels[$labelId][1];
		}
	}

	private function binaryOp($opCode)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 2)
			throw new \LogicException("Stack too short for binary operator");
		$this->ops[] = func_get_args();
		--$this->stackH;
		return $this;
	}
	private function unaryOp($opCode)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 1)
			throw new \LogicException("Stack too short for unary operator");
		$this->ops[] = func_get_args();;
		return $this;
	}
	private function nullaryOp($opCode)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		$this->ops[] = func_get_args();
		++$this->stackH;
		return $this;
	}

	// pop b, a; push a + b
	public function add()
	{
		return $this->binaryOp(OpCodes::ADD);
	}
	// pop b, a; push a => b
	public function arrow()
	{
		return $this->binaryOp(OpCodes::ARROW);
	}
	// pop b, a; push a & b
	public function bAnd()
	{
		return $this->binaryOp(OpCodes::BAND);
	}
	// pop a; push ~a
	public function bNot()
	{
		return $this->unaryOp(OpCodes::BNOT);
	}
	// pop b, a; push a | b
	public function bOr()
	{
		return $this->binaryOp(OpCodes::BOR);
	}
	// goto labelId
	public function br($labelId)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		$this->setLabelStackH($labelId, $this->stackH);
		++$this->labels[$labelId][($this->labels[$labelId][0] === null) ? 2 : 3];
		$this->ops[] = [ OpCodes::BR, $labelId ];
		$this->stackH = null;
		return $this;
	}
	// pop a; if (!a) goto labelId
	public function brFalse($labelId)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 1)
			throw new \LogicException("Stack too short for branch-if-false");
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		--$this->stackH;
		try {
			$this->setLabelStackH($labelId, $this->stackH);
		} catch ($e) {
			++$this->stackH;
			throw $e;
		}
		++$this->labels[$labelId][($this->labels[$labelId][0] === null) ? 2 : 3];
		$this->ops[] = [ OpCodes::BRFALSE, $labelId ];
		return $this;
	}
	// pop a; if (a) goto labelId
	public function brTrue($labelId)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 1)
			throw new \LogicException("Stack too short for branch-if-false");
		if (!isset($this->labels[$labelId]))
			throw new \LogicException("Unknown label");
		--$this->stackH;
		try {
			$this->setLabelStackH($labelId, $this->stackH);
		} catch ($e) {
			++$this->stackH;
			throw $e;
		}
		++$this->labels[$labelId][($this->labels[$labelId][0] === null) ? 2 : 3];
		$this->ops[] = [ OpCodes::BRTRUE, $labelId ];
		return $this;
	}
	// pop b, a; push a ^ b
	public function bXor()
	{
		return $this->binaryOp(OpCodes::BXOR);
	}
	// pop a[argCount - 1], ..., a[0]; call fnName(a[0], ..., a[argCount - 1])
	public function call($fnName, $argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount)
			throw new \LogicException("Stack too short for function call");
		$this->ops[] = [ OpCodes::CALL, $fnName, $argCount ];
		$this->stackH -= $argCount - 1;
		return $this;
	}
	// pop b[argCount - 1], ..., b[0], a; call a(b[0], ..., b[argCount - 1])
	public function callI($argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount + 1)
			throw new \LogicException("Stack too short for indirect function call");
		$this->ops[] = [ OpCodes::CALLI, $argCount ];
		$this->stackH -= $argCount;
		return $this;
	}
	// pop b[argCount - 1], ..., b[0], a; call a->mthName(b[0], ..., b[argCount - 1])
	public function callMethod($mthName, $argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount + 1)
			throw new \LogicException("Stack too short for method call");
		$this->ops[] = [ OpCodes::CALLMETHOD, $mthName, $argCount ];
		$this->stackH -= $argCount;
		return $this;
	}
	// pop c[argCount - 1], ..., c[0], b, a; call a->b(c[0], ..., c[argCount - 1])
	public function callMethodI($argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount + 2)
			throw new \LogicException("Stack too short for indirect method call");
		$this->ops[] = [ OpCodes::CALLMETHODI, $argCount ];
		$this->stackH -= $argCount + 1;
		return $this;
	}
	// pop a[argCount - 1], ..., a[0]; call clsName::mthName(a[0], ..., a[argCount - 1])
	public function callStatic($clsName, $mthName, $argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount)
			throw new \LogicException("Stack too short for static method call");
		$this->ops[] = [ OpCodes::CALLSTATIC, $clsName, $mthName, $argCount ];
		$this->stackH -= $argCount - 1;
		return $this;
	}
	// pop b[argCount - 1], ..., b[0], a; call clsName::a(b[0], ..., b[argCount - 1])
	public function callStaticI($clsName, $argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount + 1)
			throw new \LogicException("Stack too short for indirect static method call");
		$this->ops[] = [ OpCodes::CALLSTATICI, $clsName, $argCount ];
		$this->stackH -= $argCount;
		return $this;
	}
	// pop b[argCount - 1], ..., b[0], a; call a::mthName(b[0], ..., b[argCount - 1])
	public function callStaticCi($mthName, $argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount + 1)
			throw new \LogicException("Stack too short for class-indirect static method call");
		$this->ops[] = [ OpCodes::CALLSTATICCI, $clsVarName, $mthName, $argCount ];
		$this->stackH -= $argCount;
		return $this;
	}
	// pop c[argCount - 1], ..., c[0], b, a; call a::b(c[0], ..., c[argCount - 1])
	public function callStaticCiI($argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount + 2)
			throw new \LogicException("Stack too short for indirect class-indirect static method call");
		$this->ops[] = [ OpCodes::CALLSTATICCII, $argCount ];
		$this->stackH -= $argCount + 1;
		return $this;
	}
	// pop b, a; push a == b
	public function cEq()
	{
		return $this->binaryOp(OpCodes::CEQ);
	}
	// pop b, a; push a >= b
	public function cGe()
	{
		return $this->binaryOp(OpCodes::CGE);
	}
	// pop b, a; push a > b
	public function cGt()
	{
		return $this->binaryOp(OpCodes::CGT);
	}
	// pop b, a; push a <= b
	public function cLe()
	{
		return $this->binaryOp(OpCodes::CLE);
	}
	// pop b, a; push a < b
	public function cLt()
	{
		return $this->binaryOp(OpCodes::CLT);
	}
	// pop b, a; push a != b
	public function cNe()
	{
		return $this->binaryOp(OpCodes::CNE);
	}
	// pop a; push (type)a
	public function conv($type)
	{
		return $this->unaryOp(OpCodes::CONV, $type);
	}
	// pop b, a; push a === b
	public function cStrictEq()
	{
		return $this->binaryOp(OpCodes::CSTRICTEQ);
	}
	// pop b, a; push a !== b
	public function cStrictNe()
	{
		return $this->binaryOp(OpCodes::CSTRICTNE);
	}
	// pop b, a; push a / b
	public function div()
	{
		return $this->binaryOp(OpCodes::DIV);
	}
	// pop a; push a, a
	public function dup()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 1)
			throw new \LogicException("Stack too short for duplication");
		$this->ops[] = [ OpCodes::DUP ];
		++$this->stackH;
		return $this;
	}
	// pop a; push a instanceof clsName
	public function isInst($clsName)
	{
		return $this->unaryOp(OpCodes::ISINST, $clsName);
	}
	// pop b, a; push a instanceof b
	public function isInstI()
	{
		return $this->binaryOp(OpCodes::ISINSTI);
	}
	// push clsName::cstName
	public function ldConst($clsName, $cstName)
	{
		return $this->nullaryOp(OpCodes::LDCONST, $clsName, $cstName);
	}
	// pop a; push a::cstName
	public function ldConstCi($cstName)
	{
		return $this->unaryOp(OpCodes::LDCONSTCI, $cstName);
	}
	// pop b, a; push a[b]
	public function ldElem()
	{
		return $this->binaryOp(OpCodes::LDELEM);
	}
	// pop a; push a->fldName
	public function ldFld($fldName)
	{
		return $this->unaryOp(OpCodes::LDFLD, $fldName);
	}
	// pop b, a; push a->b
	public function ldFldI()
	{
		return $this->binaryOp(OpCodes::LDFLDI);
	}
	// push clsName::$fldName
	public function ldSFld($clsName, $fldName)
	{
		return $this->nullaryOp(OpCodes::LDSFLD, $clsName, $fldName);
	}
	// pop a; push clsName::$a
	public function ldSFldI($clsName)
	{
		return $this->unaryOp(OpCodes::LDSFLDI, $clsName);
	}
	// pop a; push a::$fldName
	public function ldSFldCi($fldName)
	{
		return $this->unaryOp(OpCodes::LDSFLDCI, $fldName);
	}
	// pop b, a; push a::$b
	public function ldSFldCiI()
	{
		return $this->binaryOp(OpCodes::LDSFLDCII);
	}
	// push val
	public function ldVal($val)
	{
		return $this->nullaryOp(OpCodes::LDVAL, $val);
	}
	// push $varName
	public function ldVar($varName)
	{
		if (!$this->isLocalUsed($varName))
			throw new \LogicException("Unknown local");
		return $this->nullaryOp(OpCodes::LDVAR, $varName);
	}
	// pop a; push $a
	public function ldVarI()
	{
		return $this->unaryOp(OpCodes::LDVARI);
	}
	// pop a; push &a
	public function mkRef()
	{
		return $this->unaryOp(OpCodes::MKREF);
	}
	// pop b, a; push a * b
	public function mul()
	{
		return $this->binaryOp(OpCodes::MUL);
	}
	// pop a; push -a
	public function neg()
	{
		return $this->unaryOp(OpCodes::NEG);
	}
	// pop a[elemCount - 1], ..., a[0]; push [ a[0], ..., a[elemCount - 1] ]
	public function newArr($elemCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $elemCount)
			throw new \LogicException("Stack too short for array creation");
		$this->ops[] = [ OpCodes::NEWARR, $elemCount ];
		$this->stackH -= $elemCount - 1;
		return $this;
	}
	// pop a[argCount - 1], ..., a[0]; push new clsName(a[0], ..., a[argCount - 1])
	public function newObj($clsName, $argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount)
			throw new \LogicException("Stack too short for object creation");
		$this->ops[] = [ OpCodes::NEWOBJ, $clsName, $argCount ];
		$this->stackH -= $argCount - 1;
		return $this;
	}
	// pop b[argCount - 1], ..., b[0], a; push new a(b[0], ..., b[argCount - 1])
	public function newObjI($argCount)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < $argCount + 1)
			throw new \LogicException("Stack too short for indirect object creation");
		$this->ops[] = [ OpCodes::NEWOBJ, $argCount ];
		$this->stackH -= $argCount;
		return $this;
	}
	// pop a; push !a
	public function not()
	{
		return $this->unaryOp(OpCodes::NOT);
	}
	// pop a
	public function pop()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 1)
			throw new \LogicException("Stack already empty");
		$this->ops[] = [ OpCodes::POP ];
		--$this->stackH;
		return $this;
	}
	// pop b, a; push a ** b
	public function power()
	{
		return $this->binaryOp(OpCodes::POWER);
	}
	// pop a; push @a
	public function quiet()
	{
		return $this->unaryOp(OpCodes::QUIET);
	}
	// pop b, a; push a % b
	public function rem()
	{
		return $this->binaryOp(OpCodes::REM);
	}
	// try-pop a; ensure stack empty; return a
	public function ret()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH > 1)
			throw new \LogicException("Stack must contain at most one element when returning");
		$this->ops[] = [ OpCodes::RET ];
		$this->stackH = null;
		return $this;
	}
	// pop b, a; push a << b
	public function shl()
	{
		return $this->binaryOp(OpCodes::SHL);
	}
	// pop b, a; push a >> b
	public function shr()
	{
		return $this->binaryOp(OpCodes::SHR);
	}
	// pop a; push ...a
	public function spread()
	{
		return $this->unaryOp(OpCodes::SPREAD);
	}
	// pop c, b, a; a[b] = c
	public function stElem()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 3)
			throw new \LogicException("Stack too short for element assignment");
		$this->ops[] = [ OpCodes::STELEM ];
		$this->stackH -= 3;
		return $this;
	}
	// pop b, a; a->fldName = b
	public function stFld($fldName)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 2)
			throw new \LogicException("Stack too short for field assignment");
		$this->ops[] = [ OpCodes::STFLD, $fldName ];
		$this->stackH -= 2;
		return $this;
	}
	// pop c, b, a; a->b = c
	public function stFldI()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 3)
			throw new \LogicException("Stack too short for indirect field assignment");
		$this->ops[] = [ OpCodes::STFLDI ];
		$this->stackH -= 3;
		return $this;
	}
	// pop b, a; a[] = b
	public function stNewElem()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 2)
			throw new \LogicException("Stack too short for new element assignment");
		$this->ops[] = [ OpCodes::STNEWELEM ];
		$this->stackH -= 2;
		return $this;
	}
	// pop b, a; push a . b
	public function strCat()
	{
		return $this->binaryOp(OpCodes::STRCAT);
	}
	// pop a; clsName::$fldName = a
	public function stSFld($clsName, $fldName)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 1)
			throw new \LogicException("Stack too short for static field assignment");
		$this->ops[] = [ OpCodes::STSFLD, $clsName, $fldName ];
		--$this->stackH;
		return $this;
	}
	// pop b, a; clsName::$a = b
	public function stSFldI($clsName)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 2)
			throw new \LogicException("Stack too short for indirect static field assignment");
		$this->ops[] = [ OpCodes::STSFLDI, $clsName ];
		$this->stackH -= 2;
		return $this;
	}
	// pop b, a; a::$fldName = b
	public function stSFldCi($fldName)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 2)
			throw new \LogicException("Stack too short for class-indirect static field assignment");
		$this->ops[] = [ OpCodes::STSFLDCI, $fldName ];
		$this->stackH -= 2;
		return $this;
	}
	// pop c, b, a; a::$b = c
	public function stSFldCiI()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 3)
			throw new \LogicException("Stack too short for indirect class-indirect static field assignment");
		$this->ops[] = [ OpCodes::STSFLDCII ];
		$this->stackH -= 3;
		return $this;
	}
	// pop a; $varName = a
	public function stVar($varName)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 1)
			throw new \LogicException("Stack too short for variable assignment");
		if (!$this->isLocalUsed($varName))
			throw new \LogicException("Unknown local");
		$this->ops[] = [ OpCodes::STVAR, $varName ];
		--$this->stackH;
		return $this;
	}
	// pop b, a; $a = b
	public function stVarI($varName)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH < 2)
			throw new \LogicException("Stack too short for indirect variable assignment");
		$this->ops[] = [ OpCodes::STVARI ];
		$this->stackH -= 2;
		return $this;
	}
	// pop b, a; push a - b
	public function sub()
	{
		return $this->binaryOp(OpCodes::SUB);
	}
	// pop a; throw a
	public function throwEx()
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH != 1)
			throw new \LogicException("Stack must contain exactly one element when throwing");
		$this->ops[] = [ OpCodes::THROWEX ];
		$this->stackH = null;
		return $this;
	}
	public function tryEx($catchLabelId, $exVarName, $finallyLabelId, $endLabelId)
	{
		if ($this->stackH === null)
			throw new \LogicException("Dead code");
		if ($this->stackH != 0)
			throw new \LogicException("Stack must be empty when entering a try block");
		if ($catchLabelId === null && $finallyLabelId === null)
			throw new \LogicException("A try block must have at least a catch block or a finally block");
		if ($endLabelId === null)
			throw new \LogicException("A try block must have an end label");
		if ($catchLabelId !== null) {
			if (!isset($this->labels[$catchLabelId]))
				throw new \LogicException("Unknown label");
			if ($this->labels[$catchLabelId][0] !== null)
				throw new \LogicException("Catch block label must not be marked when opening a try block");
			$this->labels[$catchLabelId][1] = 0;
			++$this->labels[$catchLabelId][2];
			if ($exVarName === null)
				throw new \LogicException("There must be an exception variable if there is a catch block");
			if (!$this->isLocalUsed($exVarName))
				throw new \LogicException("Unknown local");
		}
		if ($finallyLabelId !== null) {
			if (!isset($this->labels[$finallyLabelId]))
				throw new \LogicException("Unknown label");
			if ($this->labels[$finallyLabelId][0] !== null)
				throw new \LogicException("Finally block label must not be marked when opening a try block");
			$this->labels[$finallyLabelId][1] = 0;
			++$this->labels[$finallyLabelId][2];
		}
		if (!isset($this->labels[$endLabelId]))
			throw new \LogicException("Unknown label");
		if ($this->labels[$endLabelId][0] !== null)
			throw new \LogicException("Finally block label must not be marked when opening a try block");
		$this->labels[$endLabelId][1] = 0;
		++$this->labels[$endLabelId][2];
		$this->ops[] = [ OpCodes::TRYEX, $catchLabelId, $exVarName, $finallyLabelId, $endLabelId ];
		return $this;
	}

	public function generate(PHPEmitter $emitter)
	{
		$stack = [ ];
		foreach ($this->ops as $pc => $op) {
			if (isset($this->targets[$pc]))
				$emitter->emitln('// L' . $pc . ':');
			$emitter->emit('// ' . OpCodes::getName($op[0]));
			$first = true;
			foreach ($op as $arg) {
				if ($first) {
					$first = false;
					continue;
				}
				$emitter->emit(' ')->emitValue($arg);
			}
			$emitter->emitln();
			switch ($op[0]) {
				case OpCode::ADD:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 14, false, '+', $o1, $o2 ];
					break;
				case OpCodes::ARROW:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ -1, false, '=>', $o1, $o2 ];
					break;
				case OpCodes::BAND:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 10, false, '&', $o1, $o2 ];
					break;
				case OpCodes::BNOT:
					$o1 = array_pop($stack);
					$stack[] = [ 18, true, '~', $o1 ];
					break;
				case OpCodes::BOR:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 8, false, '|', $o1, $o2 ];
					break;
				case OpCodes::BR:
					// TODO
					break;
				case OpCodes::BRFALSE:
					// TODO
					break;
				case OpCodes::BRTRUE:
					// TODO
					break;
				case OpCodes::BXOR:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 9, false, '^', $o1, $o2 ];
					break;
				case OpCodes::CALL:
					// TODO
					break;
				case OpCodes::CALLI:
					// TODO
					break;
				case OpCodes::CALLMETHOD:
					// TODO
					break;
				case OpCodes::CALLSTATIC:
					// TODO
					break;
				case OpCodes::CALLSTATICI:
					// TODO
					break;
				case OpCodes::CALLSTATICCI:
					// TODO
					break;
				case OpCodes::CALLSTATICCII:
					// TODO
					break;
				case OpCodes::CEQ:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 11, null, '==', $o1, $o2 ];
					break;
				case OpCodes::CGE:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 12, null, '>=', $o1, $o2 ];
					break;
				case OpCodes::CGT:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 12, null, '>', $o1, $o2 ];
					break;
				case OpCodes::CLE:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 12, null, '<=', $o1, $o2 ];
					break;
				case OpCodes::CLT:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 12, null, '<', $o1, $o2 ];
					break;
				case OpCodes::CNE:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 11, null, '!=', $o1, $o2 ];
					break;
				case OpCodes::CONV:
					$o1 = array_pop($stack);
					$stack[] = [ 18, true, '(' . $op[1] . ')', $o1 ];
					break;
				case OpCodes::CSTRICTEQ:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 11, null, '===', $o1, $o2 ];
					break;
				case OpCodes::CSTRICTNE:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 11, null, '!==', $o1, $o2 ];
					break;
				case OpCodes::DIV:
					$o2 = array_pop($stack);
					$o1 = array_pop($stack);
					$stack[] = [ 15, false, '/', $o1, $o2 ];
					break;
			}
		}
	}
}
