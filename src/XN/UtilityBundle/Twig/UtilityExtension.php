<?php

namespace XN\UtilityBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use XN\Common\DateFormat;
use XN\UtilityBundle\TokenParser\VariableTokenParser;

class UtilityExtension extends \Twig_Extension
{
    const INFLECTOR_CLASS = 'XN\Common\Inflector';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getTokenParsers()
    {
        return array(new VariableTokenParser());
    }

    public function getGlobals()
    {
        return array(
            'variables' => $this->container->get('variables'),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('es6_asset', [$this, 'es6Asset']),
            new \Twig_SimpleFunction('asset_data', [$this, 'assetData']),
            new \Twig_SimpleFunction('inline_asset', [$this, 'inlineAsset']),
            new \Twig_SimpleFunction('isset_trans', [$this->container->get('translator'), 'has']),
            new \Twig_SimpleFunction('locales', [$this->container->get('translator'), 'getLocales']),
            new \Twig_SimpleFunction('once', [$this, 'once']),
            new \Twig_SimpleFunction('is_current_page', [$this, 'isCurrentPage']),
            new \Twig_SimpleFunction('region', [$this, 'getRegion']),
            new \Twig_SimpleFunction('is_object', 'is_object'),
            new \Twig_SimpleFunction('class_of', [$this, 'getClassName']),
            new \Twig_SimpleFunction('block_from', [$this, 'blockFrom'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('call', [$this, 'callClass']),
            new \Twig_SimpleFunction('variable', [$this->container->get('variables'), 'get']),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('dechex', array($this, 'dechex')),
            new \Twig_SimpleFilter('tableize', [self::INFLECTOR_CLASS, 'tableize']),
            new \Twig_SimpleFilter('classify', [self::INFLECTOR_CLASS, 'classify']),
            new \Twig_SimpleFilter('camelize', [self::INFLECTOR_CLASS, 'camelize']),
            new \Twig_SimpleFilter('pluralize', [self::INFLECTOR_CLASS, 'pluralize']),
            new \Twig_SimpleFilter('singularize', [self::INFLECTOR_CLASS, 'singularize']),
            new \Twig_SimpleFilter('slugify', [self::INFLECTOR_CLASS, 'slugify']),
            new \Twig_SimpleFilter('bin2hex', 'bin2hex'),
            new \Twig_SimpleFilter('hex2bin', 'hex2bin'),
            new \Twig_SimpleFilter('is_array', 'is_array'),
            new \Twig_SimpleFilter('date_format', [$this, 'formatDate']),
            new \Twig_SimpleFilter('base64url_encode', array('XN\Common\UrlSafeBase64', 'encode')),
            new \Twig_SimpleFilter('base64url_decode', array('XN\Common\UrlSafeBase64', 'decode')),
            new \Twig_SimpleFilter('convert_to_html', [$this, 'convertBBCodeToHTML'], ['is_safe' => ['html']]),
        );
    }

    public function dechex($dec, $limiter = true)
    {
        $hex = str_pad(dechex($dec & 16777215), 6, '0', STR_PAD_LEFT);

        if ($limiter) {
            return '#'.$hex;
        } else {
            return $hex;
        }
    }

    public function getName()
    {
        return 'xn.twig_extension';
    }

    public function es6Asset($path, $packageName = null)
    {
        $req = $this->container->get('request_stack')->getCurrentRequest();

        if ($req && $req->cookies->get('has-es6') == '1') {
            $path = strtr($path, array('.js' => '.es6'));
        }

        return $this->container->get('templating.helper.assets')->getUrl($path, $packageName);
    }

    private function assetPath($path)
    {

        // FIXME : Maybe there's a cleaner way ?

        return dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    public function assetData($path, $mime)
    {
        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($this->assetPath($path)));
    }

    public function inlineAsset($path)
    {
        return file_get_contents($this->assetPath($path));
    }

    public function once($key)
    {
        static $keys = null;

        if ($keys === null) {
            $keys = array();
        }

        if (isset($keys[$key])) {
            return false;
        }

        $keys[$key] = $key;

        return true;
    }

    public function isCurrentPage($route)
    {
        $req = $this->container->get('request_stack')->getCurrentRequest();

        $currentRoute = $req->attributes->get('_route');

        if ($currentRoute == $route) {
            return 'class="active"';
        } else {
            return false;
        }
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

    public function blockFrom($templates, $block, $context)
    {
        $twig = $this->container->get('twig');
        $templates = (array) $templates;

        foreach ($templates as $template) {
            try {
                return $twig->loadTemplate($template)->renderBlock($block, $context);
            } catch (\Exception $e) {
                if ((!$e instanceof \InvalidArgumentException) && !($e instanceof \Twig_Error_Loader)) {
                    throw $e;
                }
            }
        }
        throw new \InvalidArgumentException('No template were found.');
    }

    public function convertBBCodeToHTML($source, $language = 'simple_bbcode')
    {
        $lg = $this->container->get($language);
        if (!($lg instanceof \XN\BBCode\Language)) {
            throw new \LogicException('%s service is not a language', $language);
        }

        return nl2br($lg->convertToHTML($source));
    }

    public function constant($class, $name)
    {
        return $class::$name;
    }

    public function callClass($class, $method, $params = array())
    {
        return call_user_func_array([$class, $method], (array) $params);
    }
}
