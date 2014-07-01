<?php

namespace Dof\ImpExpBundle;

abstract class FixedURLScraper
{
    protected $contents;

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

    public function getContents()
    {
        return $this->contents;
    }
}
