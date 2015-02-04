<?php

namespace Dof\Bundle\ImpExpBundle;

abstract class FixedURLScraper extends URLScraper
{
    protected function __construct()
    {
        $this->contents = file_get_contents(static::URL);
    }

    public static function getInstance()
    {
        static $inst = null;
        if ($inst === null)
            $inst = new static();
        return $inst;
    }

    public function getURL()
    {
        return static::URL;
    }
}
