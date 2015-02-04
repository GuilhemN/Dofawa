<?php
namespace Dof\Bundle\CharacterBundle\Twig;

use XN\Graphics\Color;

use Dof\Bundle\CharacterBundle\Areas;

class EffectExtension extends \Twig_Extension
{
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('area_shape', array('Dof\Bundle\CharacterBundle\Areas', 'getShape')),
			new \Twig_SimpleFunction('area_params', array('Dof\Bundle\CharacterBundle\Areas', 'getParams')),
			new \Twig_SimpleFunction('cast_area', array('Dof\Bundle\CharacterBundle\Areas', 'makeCastArea'))
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
