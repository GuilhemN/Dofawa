<?php

namespace XN\Grammar;

interface Reader
{
    public function getConsumedBytes();
    public function getRemainingBytes();
    public function isEof();
    // BEGIN Partially deprecated : use transact for transactions
    public function getState();
    public function setState($opaqueState);
    public function freeState($opaqueState);
    // END Partially deprecated
    public function transact($transactionFn);

    public function eat($string, $caseInsensitive = false);
    public function eatAny($strings, $caseInsensitive = false);
    // Performance tip : you may want to use the "A" (PCRE_ANCHORED)
    // modifier on your regular expression ! E. g. : "/foo/A"
    public function eatRegex($pcrePattern, $flags = 0);
    public function eatSpan($mask, $length = null);
    public function eatCSpan($mask, $length = null);
    public function eatWhiteSpace();
    public function eatToEnd();

    public function read($byteCount, $allowIncomplete = false);
    public function peek($byteCount, $allowIncomplete = false);
    public function skip($bytecount, $allowIncomplete = false);
}
