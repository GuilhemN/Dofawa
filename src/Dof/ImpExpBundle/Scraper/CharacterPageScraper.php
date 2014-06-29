<?php

namespace Dof\ImpExpBundle\Scraper;

use Dof\GraphicsBundle\EntityLook;

use Dof\ImpExpBundle\URLFamilyScraper;

class CharacterPageScraper extends URLFamilyScraper
{
    const URL_FAMILY_REGEX = '~^http://www\.dofus\.com/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+$~';

    private $entityLook;

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
