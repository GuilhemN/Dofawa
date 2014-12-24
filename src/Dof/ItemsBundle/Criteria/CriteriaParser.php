<?php
namespace Dof\ItemsBundle\Criteria;

use XN\Grammar\Reader;
use XN\Grammar\StringReader;

class CriteriaParser
{
    private function __construct() {}

    public static function criteria(Reader $source) {
        $state = $source->getState();
        $retval = self::orx($source);
        if (!$source->isEof())
            $retval = false;
        if (!$retval)
            $source->setState($state);
        $source->freeState($state);
        return $retval;
    }

    protected static function orx(Reader $source) {
        if (($andx = self::andx($source)) === null)
            return null;

        $or = [];
        while (1) {
            $state = $source->getState();
            $return = $source->eat('|') && ($o = self::andx($source)) ? $o : null;

            if(!$return)
                $source->setState($state);
            else
                $or [] = $o;
            $source->freeState($state);
            if(!$return)
                break;
        }
        return !empty($or) ? new OrCriterion(array_merge([ $andx ], $or)) : $andx;
    }

    protected static function andx(Reader $source) {
        if (($primary = self::primary($source)) === null)
            return null;
        $and = [];
        while (1) {
            $state = $source->getState();
            $return = $source->eat('&') && ($a = self::primary($source)) ? $a : null;
            if(!$return)
                $source->setState($state);
            else
                $and[] = $return;

            $source->freeState($state);
            if(!$return)
                break;
        }
        return !empty($and) ? new AndCriterion(array_merge([ $primary ], $and)) : $primary;
    }

    protected static function primary(Reader $source) {
        $return = self::simple($source);
        if(!$return){
            $state = $source->getState();
            $return = $source->eat('(') && ($orx = self::orx($source)) && $source->eat(')') ? $orx : null;

            if (!$return)
                $source->setState($state);
            $source->freeState($state);
        }
        return $return;
    }

    protected static function simple(Reader $source) {
        $state = $source->getState();
        $_1 = self::characteristic($source);
        $_2 = $_1 ? self::operator($source) : null;
        $_3 = $_2 ? self::params($source) : null;
        if (!$_3)
            $source->setState($state);
        $source->freeState($state);
        return $_3 ? new SimpleCriterion ($_1, $_2, $_3) : null;
    }

    protected static function characteristic(Reader $source) {
        $characteristic = $source->eatRegex('#[A-Za-z0-9\*]{2}#A');
        return $characteristic ? $characteristic[0] : null;
    }

    protected static function operator(Reader $source) {
        $operator = $source->eatRegex('#[=!<>~X]#A');
        return $operator ? $operator[0] : null;
    }

    protected static function params(Reader $source){
        if (($param = self::param($source)) === null)
            return null;

        $params = [ $param ];
        while (1) {
            $state = $source->getState();
            $p = $source->eat(',') ? self::param($source) : null;
            if(!$p)
                $source->setState($state);
            else
                $params[] = $p;
            $source->freeState($state);
            if(!$p) break;
        }
        return $params;
    }

    protected static function param(Reader $source) {
        $param = $source->eatRegex('#[A-Za-z0-9-]+#A');
        return $param ? $param[0] : null;
    }
}
