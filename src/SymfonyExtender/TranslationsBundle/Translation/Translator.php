<?php

namespace SymfonyExtender\TranslationsBundle\Translation;

use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\DependencyInjection\ContainerInterface;

use XN\UtilityBundle\ArrayUtility;

class Translator extends BaseTranslator {

    private $language_fallbacks = array();
    private $language_fallbacks_by_domain = array();

    private $selector;

    public function __construct(ContainerInterface $container, MessageSelector $selector, $loaderIds = array(), array $options = array()) {
        parent::__construct($container, $selector, $loaderIds, $options);

        $this->selector = $selector;

        if($container->hasParameter('language_fallbacks'))
            $this->language_fallbacks = (array) $container->getParameter('language_fallbacks');

        if($container->hasParameter('language_fallbacks_by_domain'))
            $this->language_fallbacks_by_domain = (array) $container->getParameter('language_fallbacks_by_domain');
    }

    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null) {
        if ($locale === null)
            $locale = $this->getLocale();

        if (!isset($this->catalogues[$locale]))
            $this->loadCatalogue($locale);

        $id = (array)$id;

        foreach ($id as $id1)
            if ($this->catalogues[$locale]->has((string) $id1, $domain))
                return strtr($this->catalogues[$locale]->get((string) $id1, $domain), $parameters);

        //Si les fallbacks par domaine sont renseignés
        if (isset($this->language_fallbacks_by_domain[$domain])) {
            foreach ($id as $id1) {
                $return = $this->searchfallbacks($this->language_fallbacks_by_domain[$domain], $id1, $parameters, $domain, $locale);

                if ($return)
                    return $return;
            }
        }

        //Si les fallbacks générals sont renseignés
        if (is_array($this->language_fallbacks)) {
            foreach ($id as $id1) {
                $return = $this->searchfallbacks($this->language_fallbacks, $id1, $parameters, $domain, $locale);

                if ($return)
                    return $return;
            }
        }

        foreach($id as $id1)
          return strtr($id1, $parameters);
    }
    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        if (null === $locale) {
            $locale = $this->getLocale();
        }

        if (null === $domain) {
            $domain = 'messages';
        }

        if (!isset($this->catalogues[$locale])) {
            $this->loadCatalogue($locale);
        }

        $id = (array) $id;

        foreach ($id as $id1)
            if ($this->catalogues[$locale]->has((string) $id1, $domain))
                return strtr($this->selector->choose($this->catalogues[$locale]->get($id1, $domain), (int) $number, $locale), $parameters);

        //Si les fallbacks par domaine sont renseignés
        if (isset($this->language_fallbacks_by_domain[$domain])) {
            foreach ($id as $id1) {
                $return = $this->searchfallbacks($this->language_fallbacks_by_domain[$domain], $id1, $parameters, $domain, $locale, 'transchoice', $number);
                if ($return)
                    return $return;
            }
        }

        //Si les fallbacks générals sont renseignés
        if (is_array($this->language_fallbacks)) {
            foreach ($id as $id1) {
                $return = $this->searchfallbacks($this->language_fallbacks, $id1, $domain, $parameters, $locale, 'transchoice', $number);

                if ($return)
                    return $return;
            }
        }
        return strtr($id[0], $parameters);
    }

    private function searchfallbacks(array $array, $id, array $parameters, $domain, $locale, $type = 'trans', $number = 0){

        $array = self::getForLocale($array, $locale);
        if ($array === null) //Continue si rien n'a été renseignée pour la locale en cours
            return false;
        //Renvoi la traduction ou l'id si échec
        return $this->searchTraduction($array, $id, $parameters, $domain, $type, $number);
    }

    private static function getForLocale(array $array, $locale)
    {
        if (!ArrayUtility::isAssociative($array))
            return $array;
        if (!isset($array[$locale]))
            return null;
        return $array[$locale];
    }

    private function searchTraduction(array $array, $id, array $parameters, $domain, $type = 'trans', $number = 0){
        foreach($array as $v){
            if (!isset($this->catalogues[$v]))
                $this->loadCatalogue($v);

            if($this->catalogues[$v]->has((string) ($id), $domain))
                if($type == 'trans')
                    return strtr($this->catalogues[$v]->get((string) $id, $domain), $parameters);
                elseif($type == 'transchoice')
                    return strtr($this->selector->choose($this->catalogues[$v]->get($id, $domain), (int) $number, $v), $parameters);

        }
        return strtr($id, $parameters);
    }

    public function getLocales($domain = null)
    {
        $locale = $this->getLocale();
        if (isset($this->language_fallbacks_by_domain[$domain]))
            return array_merge([ $locale ], self::getForLocale($this->language_fallbacks_by_domain[$domain], $locale));
        if (is_array($this->language_fallbacks))
            return array_merge([ $locale ], self::getForLocale($this->language_fallbacks, $locale));
        return [ $locale ];
    }
}
