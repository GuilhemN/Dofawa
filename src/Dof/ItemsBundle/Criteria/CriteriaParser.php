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
        if (!($andx = $this->andx($source)))
            return null;

        $or = [];
        while (1) {
            $state = $source->getState();
            $_1 = $source->eat('|');
            $_2 = $this->andx($source);
            $match = $_1 && $_2;

            $source->freeState($state);
            if(!$match){
                $source->setState($state);
                break;
            }
            else
                $or[] = $_2;
        }
        return new OrCriterion(!empty($or) ? [ $andx ] + $or : $andx);
    }

    protected function andx(Reader $source) {
        if (!($primary = $this->primary($source)))
            return null;

        $and = [];
        while (1) {
            $state = $source->getState();
            $_1 = $source->eat('&');
            $_2 = $this->primary($source);
            $match = $_1 && $_2;

            if(!$match)
                $source->setState($state);
            else {
                $and[] = $_2;
            }
            $source->freeState($state);
            if(!$match)
                break;
        }
        return new AndCriterion(!empty($and) ? [ $primary ] + $and : $primary);
    }

    protected function primary(Reader $source) {
        $_1 = $this->simple($source);
        if(!$_1){
            $state = $source->getState();
            $_2_1 = $source->eat('(');
            $_2_2 = $this->orx($source);
            $_2_3 = $source->eat(')');
            $match = $_2_1 && $_2_2 && $_2_3;

            if (!$match){
                $source->setState($state);
                $_2_2 = null;
            }
            $source->freeState($state);
        }
        return $_1 ? $_1 : $_2_2;
    }

    protected function simple(Reader $source) {
        $state = $source->getState();
        $_1 = $this->characteristic($source);
        $_2 = $this->operator($source);
        $_3 = $this->params($source);
        $match = $_1 && $_2 && $_3;
        if (!$match)
            $source->setState($state);
        $source->freeState($state);
        return $match ? new SimpleCriterion ($_1, $_2, $_3) : null;
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
            $_1 = $source->eat(',');
            $_2 = $_1 ? $this->param($source) : null;

            if($_2 === null)
                $source->setState($state);
            else
                $params[] = $_2;
            $source->freeState($state);
            if($_2 === null)
                break;
        }
        return $params;
    }

    protected function param(Reader $source) {
        return $source->eatRegex('#[A-Za-z0-9-]+#A');
    }
}
