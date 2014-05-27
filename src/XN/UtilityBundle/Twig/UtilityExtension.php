<?php
namespace XN\UtilityBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class UtilityExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('es6asset', [ $this, 'es6asset' ]),
		);
	}
	
	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('singularize', [ $this, 'singularize' ]),
		);
	}

	public function getName()
	{
		return 'xn.utility.twig_extension';
	}

	public function singularize($str)
	{
		return preg_replace('/^(\\S+)s(?:$|(?=\\s))/i', '\1', $str);
	}
	
	public function es6asset($path, $packageName = null)
    {
    	$req = $this->container->get('request_stack')->getCurrentRequest();
    	if ($req && $req->cookies->get('has-es6') == '1')
    		$path = strtr($path, array('.js' => '.es6.js'));
        return $this->container->get('templating.helper.assets')->getUrl($path, $packageName);
    }
}