<?php

namespace Dof\Bundle\ImpExpBundle\Scraper;

use Dof\Bundle\ImpExpBundle\CharacterPageScraper as BaseScraper;
use Symfony\Component\DependencyInjection\ContainerBuilder;

// CPS = Character Page Scraper
class FullCPS extends BaseScraper
{
    const URL_FAMILY_REGEX = '~^http://www\.dofus\.com/[0-9a-z-]+/([0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+)(/[0-9a-z-]+)?$~';

    private $items;

    public function __construct($url)
    {
        preg_match(static::URL_FAMILY_REGEX, $url, $matches);

        $url = 'http://www.dofus.com/fr/' . $matches[1] . '/caracteristiques';
        parent::__construct($url);
    }

    public function getItems()
    {
        if ($this->items === null) {
            $doc = $this->getContentsAsHTMLDocument();
            $this->items = false;
            if ($doc !== null) {
                foreach ($doc->getElementsByTagName('span') as $span) {
                    if (strpos(' ' . $span->getAttribute('class') . ' ', ' ak-linker ') === false)
                        continue;

                    $data = $span->getAttribute('data-hasqtip');
                    preg_match('~^linker_item_([0-9]+)$~', $data, $matches);

                    $idsItems[] = $matches[1];
                }

                $container = new ContainerBuilder();
                $repository = $container->get('doctrine.orm.default_entity_manager')->getRepository('DofItemBundle:ItemTemplate');

                $this->items = $repository->findById($idItems);
            }
        }
        if ($this->items === false)
            return null;
        return $this->items;
    }
}
