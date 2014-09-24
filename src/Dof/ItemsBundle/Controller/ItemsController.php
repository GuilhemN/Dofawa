<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemTemplate;

class ItemsController extends Controller
{
    public function indexAction($page) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemTemplate');

        $perPage = 10;

        $count = $repo->countTotal();
        $items = $repo->findBy([], [], 10, ($page - 1) * $perPage);

        $pagination = array(
   			'page' => $page,
   			'route' => 'dof_items_homepage',
  			'pages_count' => ceil($count / $perPage),
   			'route_params' => array()
   		);

        return $this->render('DofItemsBundle:Items:index.html.twig', [
            'items' => $items,
            'count' => $count,
            'pagination' => $pagination
            ]);
    }

    /**
     * @ParamConverter("item", options={"repository_method" = "findOneWithJoins"})
     */
    public function showAction(ItemTemplate $item)
    {
        return $this->render('DofItemsBundle:Items:show.html.twig', ['item' => $item]);
    }
}
