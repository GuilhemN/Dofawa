<?php

namespace Dof\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Dof\TranslationBundle\Entity\Translation;

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

    public function createAction($tLocale, $domain, $label){
        $user = $this->get('security.context')->getToken()->getUser();
        $translator = $this->get('translator');

        if($user == null or !$translator->has($label, $domain, 'fr'))
            throw new AccessDeniedException();

        $translation = new Translation();

        $translation
          ->setLabel($label)
          ->setdomain($domain)
          ->setLocale($tLocale)
        ;

        $form = $this->createForm('dof_translation', $translation);

        return $this->render('DofTranslationBundle:Translation:create.html.twig', ['form' => $form->createView()]);
    }

    private function domainExclude(){
        return array(
            'FOSUserBundle'
        );
    }
}
