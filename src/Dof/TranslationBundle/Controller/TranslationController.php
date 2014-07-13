<?php

namespace Dof\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslationController extends Controller
{
    public function localeAction($tLocale)
    {
        $translator = $this->get('translator');

        $baseCatalogue = $translator->getCatalogue(null, 'fr');

        return $this->render('DofTranslationBundle:Translation:locale.html.twig', array(
          'baseCatalogue' => $baseCatalogue,
          'locale' => $tLocale,
          'excludes' => $this->domainExclude()
        ));
    }

    private function domainExclude(){
        return array(
            'FOSUserBundle'
        );
    }
}
