<?php

namespace Dof\ItemsManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\ItemsBundle\Entity\ItemTemplate;
use Dof\ItemsManagerBundle\Entity\Item;
use Dof\ItemsManagerBundle\Form\InventorySearch;
use Dof\UserBundle\Entity\User;

class ItemsManagerController extends Controller
{
    public function indexAction($page) {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $form = $this->createForm(new InventorySearch());
        $form->handleRequest($this->get('request'));

        $params = $this->getItems($form->getData(), $page); 

        return $this->render('DofItemsManagerBundle:ItemsManager:index.html.twig',
            ['form' => $form->createView()] + $params
            );
    }

    protected function getItems($options, $page, array $params = array()) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsManagerBundle:Item');
        $full = $this->get('security.context')->isGranted('ROLE_SPELL_XRAY');

        $perPage = 15;
        $user = $this->getUser();

        $locale = $this->get('translator')->getLocale();
        $count = $repo->countWithOptions($options, $user, $locale);
        $items = $repo->findWithOptions($options, $user, ['level' => 'DESC', 'name' . ucfirst($this->get('request')->getLocale()) => 'ASC'], $perPage, ($page - 1) * $perPage, $locale, 'normal', $full);

        $pagination = array(
            'page' => $page,
            'route' => $this->get('request')->attributes->get('_route'),
            'pages_count' => ceil($count / $perPage),
            'route_params' => $params
        );

        return array(
            'count' => $count,
            'items' => $items,
            'pagination' => $pagination
        );
    }
}