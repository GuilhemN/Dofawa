<?php
namespace Dof\CharactersBundle\Twig;

use XN\Graphics\Color;

use Dof\CharactersBundle\Areas;

class EffectExtension extends \Twig_Extension
{
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('area_shape', array('Dof\CharactersBundle\Areas', 'getShape')),
			new \Twig_SimpleFunction('area_params', array('Dof\CharactersBundle\Areas', 'getParams')),
			new \Twig_SimpleFunction('cast_area', array('Dof\CharactersBundle\Areas', 'makeCastArea'))
		);
	}
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('color', array($this, 'isColor'))
        );
    }

    public function isColor($value)
    {
        return $value instanceof Color;
    }

    public function getName()
    {
        return 'effect_extension';
    }
}
