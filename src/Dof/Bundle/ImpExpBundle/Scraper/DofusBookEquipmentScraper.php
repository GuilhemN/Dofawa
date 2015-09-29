<?php

namespace Dof\Bundle\ImpExpBundle\Scraper;

use Dof\Bundle\ImpExpBundle\URLFamilyScraper;
use EXSyst\Component\XML\DOMUtils;
use Patchwork\Utf8;

function unistr_to_ords($str, $encoding = 'UTF-8'){
    // Turns a string of unicode characters into an array of ordinal values,
    // Even if some of those characters are multibyte.
    $str = mb_convert_encoding($str,"UCS-4BE",$encoding);
    $ords = array();

    // Visit each unicode character
    for($i = 0; $i < mb_strlen($str,"UCS-4BE"); $i++){
        // Now we have 4 bytes. Find their total
        // numeric value.
        $s2 = mb_substr($str,$i,1,"UCS-4BE");
        $val = unpack("N",$s2);
        $ords[] = $val[1];
    }
    return($ords);
}
class DofusBookEquipmentScraper extends URLFamilyScraper
{
    const URL_FAMILY_REGEX = '~^http://www\.dofusbook\.net/[a-z]{2}/(?:personnage|personaje)/[a-z]+/[0-9a-z-]+/[0-9]+.html$~';

    private $name;
    private $items;

    public function getName()
    {
        if ($this->name === null) {
            $doc = $this->getContentsAsHTMLDocument();
            $this->name = false;
            if ($doc !== null) {
                $node = DOMUtils::selectFirstNode($doc, '.right .left.milieu h2');
                if ($node !== null) {
                    $this->name = DOMUtils::innerHTML($node);
                }
            }
        }
        if ($this->name === false) {
            return;
        }

        return $this->name;
    }
    public function getItems()
    {
        if ($this->items === null) {
            $doc = $this->getContentsAsHTMLDocument();
            $this->items = false;
            if ($doc !== null) {
                $nodes = [
                    DOMUtils::selectFirstNode($doc, '#item-d1'),
                    DOMUtils::selectFirstNode($doc, '#item-d2'),
                    DOMUtils::selectFirstNode($doc, '#item-d3'),
                    DOMUtils::selectFirstNode($doc, '#item-d4'),
                    DOMUtils::selectFirstNode($doc, '#item-d5'),
                    DOMUtils::selectFirstNode($doc, '#item-d6'),

                    DOMUtils::selectFirstNode($doc, '#item-ar'),
                    DOMUtils::selectFirstNode($doc, '#item-ch'),
                    DOMUtils::selectFirstNode($doc, '#item-ca'),
                    DOMUtils::selectFirstNode($doc, '#item-a1'),
                    DOMUtils::selectFirstNode($doc, '#item-a2'),
                    DOMUtils::selectFirstNode($doc, '#item-am'),
                    DOMUtils::selectFirstNode($doc, '#item-ce'),
                    DOMUtils::selectFirstNode($doc, '#item-bo'),
                    DOMUtils::selectFirstNode($doc, '#item-br'),
                    DOMUtils::selectFirstNode($doc, '#item-fa'),
                ];
                foreach ($nodes as $node) {
                    if ($node === null) {
                        continue;
                    }

                    $item = ['name' => null, 'level' => null, 'iconId' => null];

                    $header = DOMUtils::selectFirstNode($node, '.item-header h2');
                    if ($header !== null) {
                        foreach ($header->childNodes as $cld) {
                            if ($cld->nodeType == XML_TEXT_NODE) {
                                $item['name'] .= $cld->nodeValue;
                            }
                        }
                        $item['name'] = Utf8::trim($item['name']);
                    }
                    $this->items[] = $item;
                }
            }
        }
        if ($this->items === false) {
            return;
        }

        return $this->items;
    }
}
