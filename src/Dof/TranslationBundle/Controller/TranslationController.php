<?php

namespace Dof\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslationController extends Controller
{
    public function localeAction($tLocale)
    {
        $translator = $this->get('translator');

        $baseCatalogue = $translator->getCatalogue('fr');
        $currentCatalogue = $translator->getCatalogue($tLocale);

        print_r($baseCatalogue);
        print_r($currentCatalogue);

        return $this->render('DofTranslationBundle:Translation:locale.html.twig', array(
          'baseCatalogue' => $baseCatalogue,
          'currentCatalogue' => $currentCatalogue,
          'locale' => $tLocale
        ));
    }
}
