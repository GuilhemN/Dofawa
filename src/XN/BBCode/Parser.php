<?php
namespace XN\BBCode;

use XN\Grammar\Reader;
use XN\Grammar\StringReader;

class Parser
{
	const DEFAULT_DOCUMENT_CLASS = 'XN\\BBCode\\Document';
	const DEFAULT_TAG_CLASS = 'XN\\BBCode\\Tag';
	const DEFAULT_TEXT_CLASS = 'XN\\BBCode\\Text';

	private $lg;
	private $documentClass;
	private $tagClass;
	private $textClass;

	public function __construct(Language $language, $documentClass = self::DEFAULT_DOCUMENT_CLASS, $tagClass = self::DEFAULT_TAG_CLASS, $textClass = self::DEFAULT_TEXT_CLASS)
	{
		$this->lg = $language;
		$this->documentClass = $documentClass;
		$this->tagClass = $tagClass;
		$this->textClass = $textClass;
	}

	public function getLanguage()
	{
		return $this->lg;
	}

	public function parse($source, Document $doc = null)
	{
		if (is_string($source))
			$source = new StringReader($source);
		if (!$this->message($source, $parts, $errCode, $errOffset))
			throw new \Exception('Parse error at position ' . $errOffset . ' : ' . ParserError::getName($errCode));
		if ($doc === null)
			$doc = $this->makeDocument();
		foreach ($parts as $part)
			$doc->appendChild($part);
		return $doc;
	}
	protected function makeDocument()
	{
		$documentClass = $this->documentClass;
		if (is_callable($documentClass))
			return call_user_func($documentClass);
		else
			return new $documentClass();
	}
	public function convertToHTML($source)
	{
		$doc = $this->parse($source);
		$htmlDoc = new \DOMDocument();
		return $htmlDoc->saveHTML($doc->toDOMNode($htmlDoc));
	}

	public function message(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		return $src->transact(function () use ($src, &$retval, &$errCode, &$errOffset) {
			return $this->parts($src, $retval, $errCode, $errOffset)
				&& $this->_eof($src, $errCode, $errOffset);
		});
	}
	private function _eof(Reader $src, &$errCode, &$errOffset)
	{
		$retval = null;
		if ($src->isEof())
			return true;
		else {
			if (!$errCode) {
				$errCode = ParserError::EXTRA_DATA;
				$errOffset = $src->getConsumedBytes();
			}
			return false;
		}
	}

	public function parts(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		$retval = [ ];
		while ($this->part($src, $part, $errCode, $errOffset))
			$retval[] = $part;
		return true;
	}

	public function part(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		return $this->text($src, $retval, $errCode, $errOffset)
			|| $this->tag($src, $retval, $errCode, $errOffset);
	}

	public function text(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		$offset = $src->getConsumedBytes();
		$text = $src->eatCSpan('[');
		if (strlen($text)) {
			$retval = $this->makeText($text, $offset);
			return true;
		} else {
			$errCode = ParserError::EXPECTED_TEXT;
			$errOffset = $src->getConsumedBytes();
			return false;
		}
	}
	protected function makeText($text, $offset)
	{
		$textClass = $this->textClass;
		if (is_callable($textClass))
			return call_user_func($textClass, $text, $offset);
		else
			return new $textClass($text, $offset);
	}

	public function tag(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		return $src->transact(function () use ($src, &$retval, &$errCode, &$errOffset) {
			$offset = $src->getConsumedBytes();
			if (!$src->eat('[')) {
				$errCode = ParserError::EXPECTED_OTAG_START;
				$errOffset = $src->getConsumedBytes();
				return false;
			}
			$idOffset = $src->getConsumedBytes();
			if (!$this->identifier($src, $tagName, $errCode, $errOffset))
				return false;
			$tpl = $this->lg->getTagTemplateByName(strtolower($tagName));
			if (!$tpl) {
				$errCode = ParserError::UNKNOWN_TAG;
				$errOffset = $idOffset;
			}
			$retval = $this->makeTag($tpl, $tagName, $offset);
			$state = $src->getState();
			try {
				if ($src->eat('=')) {
					if ($this->value($src, $tagValue, $errCode, $errOffset))
						$retval->setValue($tagValue);
					else {
						$src->freeState($state);
						return false;
					}
				}
				if ($tpl->isRaw()) {
					$pos = $src->getConsumedBytes();
					$src->setState($state);
					$rawV = $src->read($pos - $src->getConsumedBytes());
				} else
					$rawV = '';
			} catch (\Exception $ex) {
				$src->freeState($state);
				throw $ex;
			}
			$src->freeState($state);
			if (!$this->attributes($src, $tagAttrs, $errCode, $errOffset))
				return false;
			foreach ($tagAttrs as $key => $value)
				$retval->setAttribute($key, $value);
			$src->eatWhiteSpace();
			return $this->_tagRest($src, $retval, $rawV, $errCode, $errOffset);
		});
	}
	protected function makeTag(TagTemplateInterface $tpl, $tagName, $offset)
	{
		$tagClass = $this->tagClass;
		if (is_callable($tagClass))
			$retval = call_user_func($tagClass, $tpl, $tagName, $offset);
		else
			$retval = new $tagClass($tpl, $tagName, $offset);
	}
	private function _tagRest(Reader $src, Tag $retval, $rawV, &$errCode, &$errOffset)
	{
		if ($src->eat('/')) {
			if ($src->eat(']'))
				return true;
			else {
				$errCode = ParserError::EXPECTED_OTAG_END;
				$errOffset = $src->getConsumedBytes();
				return false;
			}
		} else {
			if (!$src->eat(']')) {
				$errCode = ParserError::EXPECTED_OTAG_END;
				$errOffset = $src->getConsumedBytes();
				return false;
			}
			$tpl = $retval->getTemplate();
			if ($tpl->isLeaf())
				return true;
			elseif ($tpl->isRaw())
				return $this->_rawTagRest($src, $retval, $rawV, $errCode, $errOffset);
			else
				return $this->_fullTagRest($src, $retval, $errCode, $errOffset);
		}
	}
	private function _rawTagRest(Reader $src, Tag $retval, $rawV, &$errCode, &$errOffset)
	{
		$offset = $src->getConsumedBytes();
		$ctag = '[/' . $retval->getName() . $rawV;
		$rest = $src->eatRegex('#(.*?)' . preg_quote($ctag, '#') . '#As');
		if (!$rest) {
			$errCode = ParserError::EXPECTED_CTAG_START;
			$errOffset = $offset + $src->getRemainingBytes();
			return false;
		}
		if (strlen($rest[0])) {
			try {
				$retval->appendChild($this->makeText($rest[0], $offset));
			} catch (\Exception $e) {
				$errCode = ParserError::REJECTED_NODE;
				$errOffset = $offset;
				return false;
			}
		}
		return $this->_closingTagRest($src, $retval, $errCode, $errOffset);
	}
	private function _fullTagRest(Reader $src, Tag $retval, &$errCode, &$errOffset)
	{
		if (!$this->parts($src, $parts, $errCode, $errOffset))
			return false;
		foreach ($parts as $part) {
			try {
				$retval->appendChild($part);
			} catch (\Exception $e) {
				$errCode = ParserError::REJECTED_NODE;
				$errOffset = $part->getOffset();
				return false;
			}
		}
		if (!$src->eatRegex('#' . preg_quote('[/' . $retval->getName(), '#') . '#Ai')) {
			$errCode = ParserError::EXPECTED_CTAG_START;
			$errOffset = $src->getConsumedBytes();
			return false;
		}
		if ($src->eat('=')) {
			if ($this->value($src, $endValue, $errCode, $errOffset))
				$retval->setEndValue($endValue);
			else
				return false;
		}
		return $this->_closingTagRest($src, $retval, $errCode, $errOffset);
	}
	private function _closingTagRest(Reader $src, Tag $retval, &$errCode, &$errOffset)
	{
		if (!$this->attributes($src, $endAttrs, $errCode, $errOffset))
			return false;
		foreach ($endAttrs as $key => $value)
			$retval->setEndAttribute($key, $value);
		$src->eatWhiteSpace();
		if (!$src->eat(']')) {
			$errCode = ParserError::EXPECTED_CTAG_END;
			$errOffset = $src->getConsumedBytes();
			return false;
		}
		return true;
	}

	public function attributes(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		$retval = [ ];
		while ($this->attribute($src, $attr, $errCode, $errOffset))
			$retval[$attr[0]] = $attr[1];
		return true;
	}

	public function attribute(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		return $src->transact(function () use ($src, &$retval, &$errCode, &$errOffset) {
			$src->eatWhiteSpace();
			if (!$this->identifier($src, $attrName, $errCode, $errOffset))
				return false;
			$retval = [ $attrName ];
			if ($src->eat('=')) {
				if ($this->value($src, $attrValue, $errCode, $errOffset))
					$retval[] = $tagValue;
				else
					return false;
			} else
				$retval[] = true;
			return true;
		});
	}

	public function identifier(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		$id = $src->eatRegex('#[A-Za-z0-9_-\*]+#A');
		if ($id) {
			$retval = $id[0];
			return true;
		} else {
			$errCode = ParserError::EXPECTED_IDENTIFIER;
			$errOffset = $src->getConsumedBytes();
			return false;
		}
	}

	public function value(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		if ($this->boolean($src, $retval, $errCode, $errOffset)
			|| $this->number($src, $retval, $errCode, $errOffset)
			|| $this->string($src, $retval, $errCode, $errOffset))
			return true;
		if (!$src->transact(function () use ($src) {
				return !$src->eat('"');
			}))
			return false; // use the error from {string}
		return $this->word($src, $retval, $errCode, $errOffset);
	}

	public function boolean(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		if ($src->eatAny([ 'on', 'yes', 'true' ], true)) {
			$retval = true;
			return true;
		} elseif ($src->eatAny([ 'no', 'off', 'false' ], true)) {
			$retval = false;
			return true;
		} else {
			$errCode = ParserError::EXPECTED_BOOLEAN;
			return false;
		}
	}

	public function number(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		$num = $src->eatRegex('#-?(?:0|[1-9][0-9]*)(?:\.[0-9]+)?(?:[eE][\+-]?[0-9]+)?#A');
		if ($num) {
			$retval = floatval($num[0]);
			return true;
		} else {
			$errCode = ParserError::EXPECTED_NUMBER;
			$errOffset = $src->getConsumedBytes();
			return false;
		}
	}

	public function string(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		return $src->transact(function () use ($src, &$retval, &$errCode, &$errOffset) {
			if (!$src->eat('"')) {
				$errCode = ParserError::EXPECTED_STRING;
				$errOffset = $src->getConsumedBytes();
				return false;
			}
			$data = [ ];
			for (;;) {
				if (($match = $src->eatRegex('#[^\\"]+#A')))
					$data[] = $match[0];
				elseif ($src->eat('\\')) {
					if (($chr = $src->eatAny([ '"', '\\', '/', 'a', 'b', 'e', 'f', 'n', 'r', 't', 'u', 'v', 'x' ], true))) {
						switch (strtolower($chr)) {
							case '"':
							case '\\':
							case '/':
								$data[] = $chr;
								break;
							case 'a':
								$data[] = "\x07";
								break;
							case 'b':
								$data[] = "\x08";
								break;
							case 'e':
								$data[] = "\e";
								break;
							case 'f':
								$data[] = "\f";
								break;
							case 'n':
								$data[] = "\n";
								break;
							case 'r':
								$data[] = "\r";
								break;
							case 't':
								$data[] = "\t";
								break;
							case 'u':
								if (($match = $src->eatRegex('#[0-9A-Fa-f]{1,4}#A')))
									$data[] = chr(hexdec($match[0]));
								else {
									$errCode = ParserError::INVALID_ESCAPE;
									$errOffset = $src->getConsumedBytes();
									return false;
								}
								break;
							case 'v':
								$data[] = "\v";
								break;
							case 'x':
								if (($match = $src->eatRegex('#[0-9A-Fa-f]{1,2}#A')))
									$data[] = chr(hexdec($match[0]));
								else {
									$errCode = ParserError::INVALID_ESCAPE;
									$errOffset = $src->getConsumedBytes();
									return false;
								}
								break;
						}
					} elseif (($match = $src->eatRegex('#[0-3]?[0-7]{1,2}#A')))
						$data[] = chr(octdec($match[0]));
					else {
						$errCode = ParserError::INVALID_ESCAPE;
						$errOffset = $src->getConsumedBytes();
						return false;
					}
				} elseif ($src->eat('"')) {
					if ($src->eat('"'))
						$data[] = '"';
					else
						break;
				} else {
					$errCode = ParserError::INVALID_ESCAPE;
					$errOffset = $src->getConsumedBytes();
					return false;
				}
			}
			$retval = implode('', $data);
			return true;
		});
	}

	public function word(Reader $src, &$retval, &$errCode, &$errOffset)
	{
		list($retval) = $src->eatRegex('#(?:(?>[^/\]\s]+)|/(?!\]))*#A');
		return true;
	}
}
