<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemTemplate;
use Dof\ItemsBundle\Form\ItemType;

class ItemsController extends Controller
{
    public function indexAction($page) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemTemplate');

        $form = $this->createForm(new ItemType());
        $form->handleRequest($this->get('request'));

        $perPage = 15;

        $count = $repo->countTotal();
        $items = $repo->findWithOptions($form->getData(), ['level' => 'ASC'], $perPage, ($page - 1) * $perPage);

        $pagination = array(
			'page' => $page,
			'route' => 'dof_items_homepage',
			'pages_count' => ceil($count / $perPage),
			'route_params' => array()
		);

        return $this->render('DofItemsBundle:Items:index.html.twig', [
            'items' => $items,
            'count' => $count,
            'pagination' => $pagination,
            'form' => $form->createView()
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
