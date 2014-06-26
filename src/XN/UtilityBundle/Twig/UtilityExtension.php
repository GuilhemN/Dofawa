<?php
namespace XN\UtilityBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class UtilityExtension extends \Twig_Extension
{
	const INFLECTOR_CLASS = 'Doctrine\Common\Inflector\Inflector';
	const SLUGGABLE_UPDATER_CLASS = 'XN\DataBundle\SluggableUpdater';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('es6asset', [ $this, 'es6asset' ]),
			new \Twig_SimpleFunction('locales', [ $this->container->get('translator'), 'getLocales' ])
		);
	}

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('tableize', [ self::INFLECTOR_CLASS, 'tableize' ]),
			new \Twig_SimpleFilter('classify', [ self::INFLECTOR_CLASS, 'classify' ]),
			new \Twig_SimpleFilter('camelize', [ self::INFLECTOR_CLASS, 'camelize' ]),
			new \Twig_SimpleFilter('pluralize', [ self::INFLECTOR_CLASS, 'pluralize' ]),
			new \Twig_SimpleFilter('singularize', [ self::INFLECTOR_CLASS, 'singularize' ]),
			new \Twig_SimpleFilter('slugify', [ self::SLUGGABLE_UPDATER_CLASS, 'slugify' ]),
		);
	}

	public function getName()
	{
		return 'xn.utility.twig_extension';
	}

	public function es6asset($path, $packageName = null)
    {
    	$req = $this->container->get('request_stack')->getCurrentRequest();
    	if ($req && $req->cookies->get('has-es6') == '1')
    		$path = strtr($path, array('.js' => '.es6'));
        return $this->container->get('templating.helper.assets')->getUrl($path, $packageName);
    }
}
