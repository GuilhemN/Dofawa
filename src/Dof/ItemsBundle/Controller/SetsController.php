<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemSet;

class SetsController extends Controller
{
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemSet');

        $sets = $repo->findWithJoins(array(), 'list');

        return $this->render('DofItemsBundle:Sets:index.html.twig', [
            'sets' => $sets
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
