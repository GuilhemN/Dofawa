<?php
namespace XN\UtilityBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

use XN\Common\DateFormat;

class UtilityExtension extends \Twig_Extension
{
	const INFLECTOR_CLASS = 'XN\Common\Inflector';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

	public function getGlobals()
	{
		return array(
			'variables' => $this->container->get('variables')
		);
	}

	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('es6_asset', [ $this, 'es6Asset' ]),
			new \Twig_SimpleFunction('asset_data', [ $this, 'assetData' ]),
			new \Twig_SimpleFunction('inline_asset', [ $this, 'inlineAsset' ]),
			new \Twig_SimpleFunction('isset_trans', [ $this->container->get('translator'), 'has' ]),
			new \Twig_SimpleFunction('locales', [ $this->container->get('translator'), 'getLocales' ]),
			new \Twig_SimpleFunction('once', [ $this, 'once' ]),
			new \Twig_SimpleFunction('is_current_page', [ $this, 'isCurrentPage' ]),
			new \Twig_SimpleFunction('region', [ $this, 'getRegion' ]),
			new \Twig_SimpleFunction('is_object', 'is_object'),
			new \Twig_SimpleFunction('class_of', [ $this, 'getClassName' ]),
			new \Twig_SimpleFunction('block_from', [ $this, 'blockFrom' ], [ 'is_safe' => [ 'html' ] ]),
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
			new \Twig_SimpleFilter('slugify', [ self::INFLECTOR_CLASS, 'slugify' ]),
			new \Twig_SimpleFilter('bin2hex', 'bin2hex'),
			new \Twig_SimpleFilter('hex2bin', 'hex2bin'),
			new \Twig_SimpleFilter('is_array', 'is_array'),
			new \Twig_SimpleFilter('date_format', [ $this, 'formatDate' ]),
			new \Twig_SimpleFilter('base64url_encode', array('XN\Common\UrlSafeBase64', 'encode')),
			new \Twig_SimpleFilter('base64url_decode', array('XN\Common\UrlSafeBase64', 'decode')),
			new \Twig_SimpleFilter('convert_to_html', [ $this, 'convertBBCodeToHTML' ], [ 'is_safe' => [ 'html' ] ]),
		);
	}

	public function getName()
	{
		return 'xn.twig_extension';
	}

	public function es6Asset($path, $packageName = null)
	{
    	$req = $this->container->get('request_stack')->getCurrentRequest();
    	if ($req && $req->cookies->get('has-es6') == '1')
    		$path = strtr($path, array('.js' => '.es6'));
        return $this->container->get('templating.helper.assets')->getUrl($path, $packageName);
	}

    private function assetPath($path)
    {
        // FIXME : Maybe there's a cleaner way ?
        return dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
    public function assetData($path, $mime)
    {
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($this->assetPath($path)));
    }
    public function inlineAsset($path)
    {
        return file_get_contents($this->assetPath($path));
    }

	public function once($key)
	{
		static $keys = null;
		if ($keys === null)
			$keys = array();
		if (isset($keys[$key]))
			return false;
		$keys[$key] = $key;
		return true;
	}

	public function isCurrentPage($route)
	{
		$req = $this->container->get('request_stack')->getCurrentRequest();

		$currentRoute = $req->attributes->get('_route');

		if($currentRoute == $route)
			return 'class="active"';
		else
			return false;
	}

	public function getRegion($locale, $in)
	{
		return locale_get_display_region($locale, $in);
	}

	public function formatDate(\Datetime $datetime, $type = 'medium', $textual = true, $locale = null)
	{
		return DateFormat::formatDate($this->container->get('translator'), $datetime, $type, $textual, $locale);
	}

	public function getClassName($class)
	{
		$nClass = get_class($class);
		$pos = strrpos($nClass, '\\');
		return ($pos === false) ? $nClass : substr($nClass, $pos + 1);
	}

	public function blockFrom($template, $block, $context)
	{
		return $this->container->get('twig')->loadTemplate($template)->renderBlock($block, $context);
	}

	public function convertBBCodeToHTML($source, $language = 'simple_bbcode') {
		$lg = $this->container->get($language);
		if(!($lg instanceof \XN\BBCode\Language))
			throw new \LogicException('%s service is not a language', $language);

		$doc = $lg->parse($source);
		$htmlDoc = new \DOMDocument();
		throw new \Exception(print_r($doc->toDOMNode($htmlDoc)->childNodes->item(1),true));
		return $lg->convertToHTML($source);
	}
}
