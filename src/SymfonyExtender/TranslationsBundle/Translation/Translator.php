<?php

    namespace SymfonyExtender\TranslationsBundle\Translation;

    use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;
    use Symfony\Component\Translation\MessageSelector;
    use Symfony\Component\DependencyInjection\ContainerInterface;

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
            
            if($this->catalogues[$locale]->has((string) ($id), $domain))
                return strtr($this->catalogues[$locale]->get((string) $id, $domain), $parameters);

            //Si les fallbacks par domaine sont renseignés
            if(isset($this->language_fallbacks_by_domain[$domain])){
                $return = $this->searchfallbacks($this->language_fallbacks_by_domain[$domain], $id, $parameters, $domain, $locale);

                if($return)
                    return $return;
            }


            //Si les fallbacks générals sont renseignés
            if(is_array($this->language_fallbacks)){
                $return = $this->searchfallbacks($this->language_fallbacks, $id, $parameters, $domain, $locale);

                if($return)
                    return $return;
            }

            return strtr($id, $parameters);            
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

            $id = (string) $id;

            if($this->catalogues[$locale]->has((string) ($id), $domain))
                return strtr($this->selector->choose($this->catalogues[$locale]->get($id, $domain), (int) $number, $locale), $parameters);

            //Si les fallbacks par domaine sont renseignés
            if(isset($this->language_fallbacks_by_domain[$domain])){
                $return = $this->searchfallbacks($this->language_fallbacks_by_domain[$domain], $id, $parameters, $domain, $locale, 'transchoice', $number);
                if($return)
                    return $return;
            }


            //Si les fallbacks générals sont renseignés
            if(is_array($this->language_fallbacks)){
                $return = $this->searchfallbacks($this->language_fallbacks, $id, $domain, $parameters, $locale, 'transchoice', $number);

                if($return)
                    return $return;
            }
            return strtr($id, $parameters);
        }

        private function searchfallbacks(array $array, $id, array $parameters, $domain, $locale, $type = 'trans', $number = 0){

                if(isset($array[$locale]))
                    //Si array simple (fallbacks général)
                    if(!is_array($array[$locale])){
                        //Renvoi la traduction ou l'id si échec
                        return $this->searchTraduction($array[$locale], $id, $parameters, $domain, $type, $number);
                    }
                    //Si multidimensionnel (pour chaque locale)
                    else{
                        //Renvoi la traduction ou l'id si échec
                        return $this->searchTraduction($array[$locale], $id, $parameters, $domain, $type, $number);
                    }

                //Continue si rien n'a été renseignée pour la locale en cours
                return false;
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
    }