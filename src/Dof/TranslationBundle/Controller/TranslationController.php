<?php

namespace Dof\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

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

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     * @Utils\UsesSession
     */
    public function createAction($tLocale, $domain, $label, Request $request){
        $translator = $this->get('translator');

        if(!$translator->has($label, $domain, 'fr'))
            throw $this->createAccessDeniedException();

        $translation = new Translation();

        $translation
          ->setLabel($label)
          ->setdomain($domain)
          ->setLocale($tLocale)
        ;

        $form = $this->createForm('dof_translation', $translation);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // Sauvegarde en bdd
            $em = $this->getDoctrine()->getManager();
            $em->persist($translation);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'translations',
                'thanks'
            );
            return $this->redirect($this->generateUrl('dof_translation_homepage', array('tLocale' => $tLocale)));
        }

        return $this->render('DofTranslationBundle:Translation:create.html.twig', ['locale' => $tLocale, 'form' => $form->createView()]);
    }

    private function domainExclude(){
        return array(
            'FOSUserBundle',
            'routes'
        );
    }
}
