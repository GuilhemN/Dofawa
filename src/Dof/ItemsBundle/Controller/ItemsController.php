<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemsController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:EquipmentTemplate');

        $item = $repo->findOneBy(array());
        return $this->render('DofItemsBundle:Items:index.html.twig', ['item' => $item]);
    }
}
