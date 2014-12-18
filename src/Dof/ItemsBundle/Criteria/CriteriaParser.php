<?php
namespace Dof\ItemsBundle\Criteria;

use XN\Grammar\Reader;
use XN\Grammar\StringReader;

class CriteriaParser
{
    public function criteria(Reader $source) {
        $state = $source->getState();
        $retval = $this->orx($source);
        if (!$source->isEof())
            $retval = false;
        if (!$retval)
            $source->setState($state);
        $source->freeState($state);
        return $retval;
    }

    protected function orx(Reader $source) {
        if (($andx = $this->andx($source)) === null)
            return null;

        $or = [];
        while (1) {
            $state = $source->getState();
            $return = $source->eat('|') && ($o = $this->andx($source)) ? $o : null;

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

    protected function andx(Reader $source) {
        if (($primary = $this->primary($source)) === null)
            return null;
        $and = [];
        while (1) {
            $state = $source->getState();
            $return = $source->eat('&') && ($a = $this->primary($source)) ? $a : null;
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

    protected function primary(Reader $source) {
        $return = $this->simple($source);
        if(!$return){
            $state = $source->getState();
            $return = $source->eat('(') && ($orx = $this->orx($source) && $source->eat(')')) ? $orx : null;

            if (!$return)
                $source->setState($state);
            $source->freeState($state);
        }
        return $return;
    }

    protected function simple(Reader $source) {
        $state = $source->getState();
        $_1 = $this->characteristic($source);
        $_2 = $_1 ? $this->operator($source) : null;
        $_3 = $_2 ? $this->params($source) : null;
        if (!$_3)
            $source->setState($state);
        $source->freeState($state);
        return $_3 ? new SimpleCriterion ($_1, $_2, $_3) : null;
    }

    protected function characteristic(Reader $source) {
        $characteristic = $source->eatRegex('#[A-Za-z0-9]{2}#A');
        return $characteristic ? $characteristic[0] : null;
    }

    protected function operator(Reader $source) {
        $operator = $source->eatRegex('#[=!<>~X]#A');
        return $operator ? $operator[0] : null;
    }

    protected function params(Reader $source){
        if (($param = $this->param($source)) === null)
            return null;

        $params = [ $param ];
        while (1) {
            $state = $source->getState();
            $p = $source->eat(',') ? $this->param($source) : null;
            if(!$p)
                $source->setState($state);
            else
                $params[] = $p;
            $source->freeState($state);
            if(!$p) break;
        }
        return $params;
    }

    protected function param(Reader $source) {
        $param = $source->eatRegex('#[A-Za-z0-9-]+#A');
        return $param ? $param[0] : null;
    }
}
