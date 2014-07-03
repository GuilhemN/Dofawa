<?php

namespace Dof\ImpExpBundle\Scraper;

use Dof\GraphicsBundle\EntityLook;

use Dof\ImpExpBundle\URLFamilyScraper;

class CharacterPageScraper extends URLFamilyScraper
{
    const URL_FAMILY_REGEX = '~^http://www\.dofus\.com/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+$~';

    private $name;
    private $serverName;
    private $entityLook;

    public function getName()
    {
        if ($this->name === null) {
            $doc = $this->getContentsAsHTMLDocument();
            $this->name = false;
            if ($doc !== null) {
                foreach ($doc->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'h1') as $h1) {
                    $this->name = '';
                    foreach ($h1->childNodes as $cld)
                        if ($cld->nodeType == XML_TEXT_NODE)
                            $this->name .= $cld->nodeValue;
                    $this->name = trim($this->name);
                    break;
                }
            }
        }
        if ($this->name === false)
            return null;
        return $this->name;
    }
    public function getServerName()
    {
        if ($this->serverName === null) {
            $doc = $this->getContentsAsHTMLDocument();
            $this->serverName = false;
            if ($doc !== null) {
                foreach ($doc->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'span') as $span) {
                    if (strpos(' ' . $span->getAttribute('class') . ' ', ' ak-directories-server-name ') === false)
                        continue;
                    $this->serverName = '';
                    foreach ($span->childNodes as $cld)
                        if ($cld->nodeType == XML_TEXT_NODE)
                            $this->serverName .= $cld->nodeValue;
                    $this->serverName = trim($this->serverName);
                    break;
                }
            }
        }
        if ($this->serverName === false)
            return null;
        return $this->serverName;
    }

    public function getEntityLook()
    {
        if ($this->entityLook === null) {
            if (preg_match('~' . EntityLook::AK_RENDERER_PATTERN . '~', $this->contents, $matches)) {
                try {
                    $this->entityLook = new EntityLook(hex2bin($matches[1]));
                } catch (\Exception $e) {
                    $this->entityLook = false;
                }
            } else
                $this->entityLook = false;
        }
        if ($this->entityLook === false)
            return null;
        return $this->entityLook;
    }
}
