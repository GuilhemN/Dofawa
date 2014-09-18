<?php
namespace XN\UtilityBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class UtilityExtension extends \Twig_Extension
{
	const INFLECTOR_CLASS = 'XN\Common\Inflector';

    private $container;
	private $translator;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->translator = $container->get('translator');
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
		);
	}

	public function getName()
	{
		return 'xn.utility.twig_extension';
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

	public function formatDate(\Datetime $datetime, $type = 'medium', $textual = true, $locale = null){
		if($type == ('short' or 'medium'))
			$format = $this->dateParams('formats.' . $textual . '.' . $type, $locale);
		else
			$format = $type;

		$infos = getdate($datetime->getTimestamp());

		if($textual) {
			$diff = $datetime->diff(new \Datetime);

			if ($type == 'medium' && ($diff->y && $diff->m) == 0 )
				if($diff->d == 1 or (date('G') < $diff->h && $diff->d == 0))
					$format = $this->dateParams('formats.1.yesterdayAt', $locale);
				elseif($diff->d == 0 && $diff->h == && $diff->i == 0)
					return $this->translator->trans('formats.1.justNow', [], 'date', $locale);
				elseif($diff->d == 0 && $diff->h <= 2)
					return $this->translator->trans('formats.1.xHoursAgo', ['%h%' => $diff->h, '%m%' => $diff->i], 'date', $locale);
				elseif($diff->d == 0)
					$format = $this->dateParams('formats.1.todayAt', $locale);
			elseif($type == 'short')
				if($diff->y == 0)
					if($diff->m == (1 || 2))
						return $this->translator->trans('formats.1.xMonthsAgo', ['%m%' => $diff->m, '%d%' => $diff->d], 'date', $locale);
					elseif($diff->m == 0 && $diff->d > 0)
						return $this->translator->trans('formats.1.xDaysAgo', ['%d%' => $diff->d], 'date', $locale);

		}

		if($infos['hours'] > 12)
			$infos['hours-12'] = $infos['hours'] - 12;
		else
			$infos['hours-12'] = $infos['hours'];

		$fields = [
			// Years
			'%Y' => $infos['year'],

			// Months
			'%B' => $this->dateParams('months.' . $infos['mon'], $locale),
			'%m' => sprintf("%02s", $infos['mon']),

			// Days
			'%A' => $this->dateParams('days.' . $infos['wday'], $locale),
			'%d' => sprintf("%02s", $infos['mday']),
			'%e' => $infos['mday'],

			// Hours
			'%H' => sprintf("%02s", $infos['hours']),
			'%l' => $infos['hours-12'],
			'%k' => $infos['hours'],

			// Minutes
			'%M' => sprintf("%02s", $infos['minutes']),

			// Seconds
			'%S' => sprintf("%02s", $infos['seconds']),
		];

		return str_replace(array_keys($fields), array_values($fields), $format);
	}

	protected function dateParams($string, $locale = null){
		return $this->translator->trans($string, [], 'date', $locale);
	}
}
