<?php
namespace XN\Grammar;

class FileReader implements Reader
{
    /**
    * @var string
    */
    private $data;

    /**
    * @var integer
    */
    private $start;

    /**
    * @var integer
    */
    private $end;

    public function __construct($data, $start = 0, $end = null)
    {
        $this->data = $data;
        fseek($data, intval($start));
        $this->start = intval($start);
        $this->end = ($end === null) ? fstat($data)['size'] : intval($end);
    }

    public function getConsumedBytes()
    {
        return ftell($this->data) - $this->start;
    }
    public function getRemainingBytes()
    {
        return $this->end - ftell($this->data);
    }
    public function isEof()
    {
        return ftell($this->data) >= $this->end;
    }
    public function getState()
    {
        return ftell($this->data);
    }
    public function setState($opaqueState)
    {
        fseek($this->data, $opaqueState);
    }
    public function freeState($opaqueState)
    {
        // no-op because our state is just an offset
    }
    public function transact($transactionFn)
    {
        if (!is_callable($transactionFn))
            return;
        $offset = ftell($this->data);
        try {
            $retval = call_user_func($transactionFn);
            if (!$retval && $retval !== null)
                fseek($this->data, $offset);
            return $retval;
        } catch (\Exception $ex) {
            fseek($this->data, $offset);
            throw $ex;
        }
    }

    public function eat($string, $caseInsensitive = false)
    {
        throw new \LogicException('not supported yet'); # TODO
    }
    public function eatAny($strings, $caseInsensitive = false)
    {
        throw new \LogicException('not supported yet'); # TODO
    }
    public function eatRegex($pcrePattern, $flags = 0)
    {
        throw new \LogicException('not supported');
    }
    public function eatSpan($mask, $length = null)
    {
        throw new \LogicException('not supported yet'); # TODO
    }
    public function eatCSpan($mask, $length = null)
    {
        throw new \LogicException('not supported yet'); # TODO
    }
    public function eatWhiteSpace()
    {
        throw new \LogicException('not supported yet'); # TODO
    }

    public function read($byteCount, $allowIncomplete = false)
    {
        if ($byteCount < 0)
            return null;
        $maxByteCount = $this->getRemainingBytes();
        if ($maxByteCount < 0 || $maxByteCount < $byteCount && !$allowIncomplete)
            return null;
        $byteCount = min($byteCount, $maxByteCount);
        return fread($this->data, $byteCount);
    }
    public function peek($byteCount, $allowIncomplete = false)
    {
        if ($byteCount < 0)
            return null;
        $maxByteCount = $this->getRemainingBytes();
        if ($maxByteCount < 0 || $maxByteCount < $byteCount && !$allowIncomplete)
            return null;
        $offset = ftell($this->data);
        $byteCount = min($byteCount, $maxByteCount);
        $string = fread($this->data, $byteCount);
        fseek($this->data, $offset);
        return $string;
    }
    public function skip($byteCount, $allowIncomplete = false)
    {
        if ($byteCount < 0)
            return 0;
        $maxByteCount = $this->getRemainingBytes();
        if ($maxByteCount < 0 || $maxByteCount < $byteCount && !$allowIncomplete)
            return 0;
        $byteCount = min($byteCount, $maxByteCount);
        fseek($this->data, ftell($this->data) + $byteCount);
        return $byteCount;
    }

    public function __toString()
    {
        return stream_get_contents($this->data);
    }
}
