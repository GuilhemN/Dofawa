<?php

namespace Dof\Bundle\ImpExpBundle\Tests\Scraper;

use Dof\Bundle\ImpExpBundle\Scraper\DofusBookEquipmentScraper;


class DofusBookEquipmentScraperTest extends \PHPUnit_Framework_TestCase
{
    private $scraper;

    public function setUp() {
        $this->scraper = new DofusBookEquipmentScraper('http://www.dofusbook.net/fr/personnage/fiche/126820-ener-getick/1.html');
    }

    public function testNameGetter() {
        $this->assertEquals('Ener-Getick', $this->scraper->getName());
    }

    public function testItemsGetter() {
        $this->assertEquals([
            ['name' => 'Savant Majeur']
        ], $this->scraper->getItems());
    }
}
