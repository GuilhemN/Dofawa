<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemSet;
use Dof\ItemsBundle\Form\ItemSetType;

class SetsController extends Controller
{
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemSet');

        $form = $this->createForm(new ItemSetType());

        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
           $query = $repo->search($form->getData());
           $sets = $query->getResult();
        }
        else
            $sets = $repo->findWithJoins([], 'list');

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
        return $this->render('DofItemsBundle:Sets:show.html.twig', [
            'set' => $set
            ]);
    }
}
