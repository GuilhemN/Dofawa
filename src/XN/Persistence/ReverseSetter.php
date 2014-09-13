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

    public static function reverseCallToMany($that, $field, $me, $original){
        if($original != $that){
            if($original !== null)
                self::reverseCall($original, 'remove' . ucfirst($field), $me);

            self::reverseCall($that, 'add' . ucfirst($field), $me);
        }
    }
}
