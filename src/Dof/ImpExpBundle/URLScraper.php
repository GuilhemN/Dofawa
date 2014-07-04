<?php

namespace Dof\ImpExpBundle;

abstract class URLScraper
{
    protected $contents;
    private $contentsAsHTMLDocument;
    private $contentsAsXMLDocument;

    public abstract function getURL();
    public function getContents()
    {
        return $this->contents;
    }
    public function getContentsAsHTMLDocument()
    {
        if ($this->contentsAsHTMLDocument === null) {
            try {
                $this->contentsAsHTMLDocument = new \DOMDocument();
                $this->contentsAsHTMLDocument->loadHTML($this->contents);
            } catch (\Exception $e) {
                $this->contentsAsHTMLDocument = false;
            }
        }
        if ($this->contentsAsHTMLDocument === false)
            return null;
        return $this->contentsAsHTMLDocument;
    }
    public function getContentsAsXMLDocument()
    {
        if ($this->contentsAsXMLDocument === null) {
            try {
                $this->contentsAsXMLDocument = new \DOMDocument();
                $this->contentsAsXMLDocument->loadXML($this->contents);
            } catch (\Exception $e) {
                $this->contentsAsXMLDocument = false;
            }
        }
        if ($this->contentsAsXMLDocument === false)
            return null;
        return $this->contentsAsXMLDocument;
    }
}
