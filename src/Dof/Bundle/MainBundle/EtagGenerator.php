<?php

namespace Dof\Bundle\MainBundle;

use XN\Metadata\EtaggableInterface;

class EtagGenerator
{
    public static function getEtag($value)
    {
        if (is_array($value)) {
            $return = '(';
            foreach ($value as $row) {
                $return .= self::getEtag($row).'/';
            }
            $return .= ')';

            return md5($return);
        } elseif (is_string($value) or is_numeric($value)) {
            return md5($value);
        } elseif ($value instanceof EtaggableInterface) {
            return md5($value->getEtag());
        } elseif ($value === null) {
            return md5('null');
        } else {
            throw new \LogicException('not implemented');
        }
    }
}
