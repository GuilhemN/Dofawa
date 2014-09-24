<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemTemplate;

class ItemsController extends Controller
{
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemTemplate');

        $count = $repo->countTotal();
        $items = $repo->findBy([], [], 10, 0);

        return $this->render('DofItemsBundle:Items:index.html.twig', ['items' => $items, 'count' => $count]);
    }

    /**
     * @ParamConverter("item", options={"repository_method" = "findOneWithJoins"})
     */
    public function showAction(ItemTemplate $item)
    {
        return $this->render('DofItemsBundle:Items:show.html.twig', ['item' => $item]);
    }
}
