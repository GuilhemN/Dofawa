<?php
namespace Dof\ItemsBundle\Twig;

use Dof\ItemsBundle\Element;

class ElementExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('element', array($this, 'getElement')),
        );
    }

    public function getElement($element)
    {
        return strtolower(Element::getName($element));
    }

    public function getName()
    {
        return 'element_extension';
    }
}