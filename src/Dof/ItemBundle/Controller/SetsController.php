<?php

namespace Dof\ItemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use XN\Annotations as Utils;

use Dof\ItemBundle\Entity\ItemSet;
use Dof\ItemBundle\Form\SetSearch;

class SetsController extends Controller
{
    /**
     * @Utils\UsesSession
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemBundle:ItemSet');

        $form = $this->createForm(new SetSearch());

        $form->handleRequest($this->get('request'));

        $searchFields = [];

        $sets = $repo->findForSearch($form->getData(), $this->get('translator')->getLocale());

        return $this->render('DofItemBundle:Sets:index.html.twig', [
            'sets' => $sets,
            'form' => $form->createView()
            ]);
    }

    /**
     * @ParamConverter("set", options={"repository_method" = "findOneWithJoins"})
     */
    public function showAction(ItemSet $set)
    {
        $characters = [];
        if($this->getUser() !== null) {
            $criteriaLoader = $this->get('dof_items.criteria_loader');
            $criteriaLoader->setEnabled(false);
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('DofBuildBundle:PlayerCharacter');

            $characters = $repo->findBy(['owner' => $this->getUser()]);
            foreach($characters as $character)
                foreach($character->getStuffs() as $stuff)
                    $stuff->getName();
            $criteriaLoader->setEnabled(true);
        }
        return $this->render('DofItemBundle:Sets:show.html.twig', [
            'set' => $set,
            'characters' => $characters
            ]);
    }
}
