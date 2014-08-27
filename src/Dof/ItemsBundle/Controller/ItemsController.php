<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemsController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:EquipmentTemplate');

        $item = $repo->findItemsWithJoins(array('level' => '199'));

        if(!isset($item[0]))
            throw $this->createNotFoundException();
        return $this->render('DofItemsBundle:Items:index.html.twig', ['item' => $item[0]]);
    }
}
