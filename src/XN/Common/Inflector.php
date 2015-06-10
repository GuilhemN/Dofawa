<?php

namespace XN\Common;

use Patchwork\Utf8;
use Doctrine\Common\Inflector\Inflector as DoctrineInflector;

class Inflector extends DoctrineInflector
{
    public static function slugify($str)
    {
        $str = Utf8::toAscii($str);
        $str = strtolower($str);
        $str = preg_replace('/[^a-z0-9-]+/', '-', $str);
        $str = trim($str, '-');

        return $str;
    }
}
