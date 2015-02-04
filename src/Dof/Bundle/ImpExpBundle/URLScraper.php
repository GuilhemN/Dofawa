<?php

namespace Dof\Bundle\ImpExpBundle;

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
            $err = libxml_use_internal_errors(true);
            try {
                $this->contentsAsHTMLDocument = new \DOMDocument();
                if (!$this->contentsAsHTMLDocument->loadHTML($this->contents))
                    $this->contentsAsHTMLDocument = false;
            } catch (\Exception $e) {
                $this->contentsAsHTMLDocument = false;
            }
            libxml_use_internal_errors($err);
        }
        if ($this->contentsAsHTMLDocument === false)
            return null;
        return $this->contentsAsHTMLDocument;
    }
    public function getContentsAsXMLDocument()
    {
        if ($this->contentsAsXMLDocument === null) {
            $err = libxml_use_internal_errors(true);
            try {
                $this->contentsAsXMLDocument = new \DOMDocument();
                if (!$this->contentsAsXMLDocument->loadXML($this->contents))
                    $this->contentsAsXMLDocument = false;
            } catch (\Exception $e) {
                $this->contentsAsXMLDocument = false;
            }
            libxml_use_internal_errors($err);
        }
        if ($this->contentsAsXMLDocument === false)
            return null;
        return $this->contentsAsXMLDocument;
    }
}
