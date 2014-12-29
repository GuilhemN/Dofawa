<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use XN\Annotations as Utils;

use Dof\ItemsBundle\Entity\ItemSet;
use Dof\ItemsBundle\Form\SetSearch;

class SetsController extends Controller
{
    /**
     * @Utils\UsesSession
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemSet');

        $form = $this->createForm(new SetSearch());

        $form->handleRequest($this->get('request'));

        $searchFields = [];

        $sets = $repo->findForSearch($form->getData(), $this->get('translator')->getLocale());

        return $this->render('DofItemsBundle:Sets:index.html.twig', [
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
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('DofBuildBundle:PlayerCharacter');

            $characters = $repo->findBy(['owner' => $this->getUser()]);
        }
        return $this->render('DofItemsBundle:Sets:show.html.twig', [
            'set' => $set,
            'characters' => $characters
            ]);
    }
}
