<?php

namespace Dof\Bundle\ImpExpBundle;

abstract class URLFamilyScraper extends URLScraper
{
    protected $url;
    protected $urlMatches;

    public function __construct($url)
    {
        if (!preg_match(static::URL_FAMILY_REGEX, $url, $matches))
            throw new \Exception('This scraper doesn\'t support this URL');
        $this->url = $url;
        $this->urlMatches = $matches;
        $this->contents = file_get_contents($url);
    }

    public function getURL()
    {
        return $this->url;
    }
}
