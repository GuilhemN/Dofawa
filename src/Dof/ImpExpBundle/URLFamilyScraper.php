<?php

namespace Dof\ImpExpBundle;

abstract class URLFamilyScraper
{
    protected $url;
    protected $contents;

    public function __construct($url)
    {
        if (!preg_match(static::URL_FAMILY_REGEX, $url, $matches))
            throw new \Exception('This scraper doesn\'t support this URL');
        $this->url = $url;
        $this->contents = file_get_contents($url);
    }

    public function getURL()
    {
        return $this->url;
    }
    public function getContents()
    {
        return $this->contents;
    }
}
