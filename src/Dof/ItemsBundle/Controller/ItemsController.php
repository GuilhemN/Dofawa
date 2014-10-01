<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemTemplate;
use Dof\ItemsBundle\Form\ItemType;
use Dof\BuildBundle\BuildSlot;

class ItemsController extends Controller
{
    public function indexAction($page) {
        $form = $this->createForm(new ItemType());
        $form->handleRequest($this->get('request'));

        $params = $this->getItems($form->getData(), $page);

        return $this->render('DofItemsBundle:Items:index.html.twig',
            ['form' => $form->createView()] + $params
            );
    }

    /**
     * @ParamConverter("item", options={"repository_method" = "findOneWithJoins"})
     */
    public function showAction(ItemTemplate $item)
    {
        return $this->render('DofItemsBundle:Items:show.html.twig', ['item' => $item]);
    }

    /**
     * @ParamConverter("stuff", class="DofBuildBundle:Stuff", options={"mapping": {"stuff" = "slug"} })
     */
    public function addItemAction($user, $character, Stuff $stuff, $type, $page){
        if(($buildSlot = BuildSlot::getValue($type)) == null)
            throw $this->createNotFoundException();

        $em = $this->getDoctrine()->getManager();
        $persoR = $em->getRepository('DofBuildBundle:PlayerCharacter');

        $perso = $persoR->findForShow($user, $character);

        if(empty($perso) or $stuff->getCharacter() != $perso)
            throw $this->createNotFoundException();

        $params = $this->getItems(['type' => BuildSlot::getItemsSlot($buildSlot)]);


        return $this->render('DofItemsBundle:Items:index.html.twig',
            [] + $params
            );
    }

    protected function getItems($options, $page) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemTemplate');

        $perPage = 15;

        $count = $repo->countWithOptions($options);
        $items = $repo->findWithOptions($options, ['level' => 'ASC'], $perPage, ($page - 1) * $perPage);

        $pagination = array(
			'page' => $page,
			'route' => 'dof_items_homepage',
			'pages_count' => ceil($count / $perPage),
			'route_params' => array()
		);
    }
}
