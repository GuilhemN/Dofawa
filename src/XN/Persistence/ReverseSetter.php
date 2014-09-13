<?php
namespace XN\Persistence;

use Doctrine\Common\Persistence\Proxy;

class ReverseSetter {
    private function __construct() { }

    public static function isProxy($that) {
        return $that instanceof Proxy;
    }

    public static function reverseCall($that, $fn, $me) {
        if ($that && !self::isProxy($that)) $that->$fn($me);
    }
}
