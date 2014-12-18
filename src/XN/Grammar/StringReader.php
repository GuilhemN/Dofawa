<?php
namespace XN\Grammar;

class StringReader implements Reader
{
    /**
     * @var string
     */
    private $data;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var integer
     */
    private $start;

    /**
     * @var integer
     */
    private $end;

    /**
     * @var integer
     */
    private $offset;

    public function __construct($data, $start = 0, $end = null)
    {
        $this->data = strval($data);
        $this->size = strlen($this->data);
        $this->start = intval($start);
        $this->end = ($end === null) ? $this->size : intval($end);
        $this->offset = $this->start;
    }

    public function getConsumedBytes()
    {
        return $this->offset - $this->start;
    }
    public function getRemainingBytes()
    {
        return $this->end - $this->offset;
    }
    public function isEof()
    {
        return $this->offset >= $this->end;
    }
    public function getState()
    {
        return $this->offset;
    }
    public function setState($opaqueState)
    {
        $this->offset = $opaqueState;
    }
	public function freeState($opaqueState)
	{
		// no-op because our state is just an offset
	}

    public function eat($string, $caseInsensitive = false)
    {
        $string = strval($string);
        $len = strlen($string);
        if ($caseInsensitive) {
            if ($this->end - $this->offset < $len)
                return null;
            if (substr_compare($this->data, $string, $this->offset, strlen($string), true) != 0)
                return null;
            $offset = $this->offset;
            $this->offset += $len;
            return substr($this->data, $offset, $len);
        } else {
            if ($this->end - $this->offset < $len)
                return false;
            if (substr_compare($this->data, $string, $this->offset, strlen($string)) != 0)
                return false;
            $this->offset += $len;
            return true;
        }
    }
    public function eatAny($strings, $caseInsensitive = false)
    {
        if ($caseInsensitive) {
            foreach ($strings as $string)
                if (($string2 = $this->eat($string, true)) !== null)
                    return $string2;
        } else {
            foreach ($strings as $string)
                if ($this->eat($string))
                    return $string;
        }
        return null;
    }
    public function eatRegex($pcrePattern, $flags = 0)
    {
        if (!preg_match($pcrePattern, $this->data, $matches, $flags | PREG_OFFSET_CAPTURE, $this->offset))
            return null;
        $offset = $this->offset;
        if ($matches[0][1] != $offset)
            return null;
        $length = strlen($matches[0][0]);
        if ($offset + $length > $this->end) {
            if (!preg_match($pcrePattern, substr($this->data, 0, $this->end), $matches, $flags | PREG_OFFSET_CAPTURE, $this->offset))
                return null;
            $offset = $this->offset;
            if ($matches[0][1] != $offset)
                return null;
            $length = strlen($matches[0][0]);
        }
        $this->offset += $length;
        if (($flags & PREG_OFFSET_CAPTURE) != 0) {
            foreach ($matches as &$match)
                $match[1] -= $offset;
        } else {
            foreach ($matches as &$match)
                $match = $match[0];
        }
        return $matches;
    }
	public function eatSpan($mask, $length = null)
	{
		$maxLength = $this->end - $this->offset;
		$length = ($length === null) ? $maxLength : min($length, $maxLength);
		$length = strspn($this->data, $mask, $this->offset, $length);
		$substr = substr($this->data, $this->offset, $length);
		$this->offset += $length;
		return $substr;
	}
	public function eatCSpan($mask, $length = null)
	{
		$maxLength = $this->end - $this->offset;
		$length = ($length === null) ? $maxLength : min($length, $maxLength);
		$length = strcspn($this->data, $mask, $this->offset, $length);
		$substr = substr($this->data, $this->offset, $length);
		$this->offset += $length;
		return $substr;
	}
    public function eatWhiteSpace()
    {
        if ($this->isEof())
            return 0;
        list($wspace) = $this->eatRegex('/\s+/A');
        return strlen($wspace);
    }

    public function read($byteCount, $allowIncomplete = false)
    {
		if ($byteCount < 0)
			return null;
        $maxByteCount = $this->getRemainingBytes();
        if ($maxByteCount < 0 || $maxByteCount < $byteCount && !$allowIncomplete)
            return null;
        $byteCount = min($byteCount, $maxByteCount);
        $substr = substr($this->data, $this->offset, $byteCount);
        $this->offset += $byteCount;
        return $substr;
    }
    public function peek($byteCount, $allowIncomplete = false)
    {
		if ($byteCount < 0)
			return null;
        $maxByteCount = $this->getRemainingBytes();
        if ($maxByteCount < 0 || $maxByteCount < $byteCount && !$allowIncomplete)
            return null;
        $byteCount = min($byteCount, $maxByteCount);
        return substr($this->data, $this->offset, $byteCount);
    }
    public function skip($byteCount, $allowIncomplete = false)
    {
		if ($byteCount < 0)
			return 0;
        $maxByteCount = $this->getRemainingBytes();
        if ($maxByteCount < 0 || $maxByteCount < $byteCount && !$allowIncomplete)
            return 0;
        $byteCount = min($byteCount, $maxByteCount);
        $this->offset += $byteCount;
        return $byteCount;
    }

    public function __toString()
    {
        return $this->data;
    }
}
