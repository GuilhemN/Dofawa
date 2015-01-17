<?php
namespace Dof\ItemsBundle\Twig;

class CriticalHitExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('criticalHit', array($this, 'getCriticalHit')),
        );
    }

    public function getCriticalHit($denominator, $agility, $bonusCC)
    {
        return (floor(($denominator - $bonusCC) * 2.9901 / Log($agility + 12));
    }

    public function getName()
    {
        return 'criticalHit_extension';
    }
}