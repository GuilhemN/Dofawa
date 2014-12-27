<?php

namespace Dof\ItemsManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use Dof\ItemsBundle\Entity\ItemTemplate;
use Dof\ItemsManagerBundle\Entity\Item;
use Dof\ItemsManagerBundle\Form\InventorySearch;
use Dof\UserBundle\Entity\User;

/**
 * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
 */
class ItemsManagerController extends Controller
{
    /**
     * @Utils\UsesSession
     */
    public function indexAction($page) {
        $form = $this->createForm(new InventorySearch());
        $form->handleRequest($this->get('request'));

        $params = $this->getItems($form->getData(), $page);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsManagerBundle:Item');

        $newName = $this->get('request')->get('changeName');
        $idNewName = $this->get('request')->get('idChange');

        if($newName != "" and $idNewName != ""){
            $item = $repo->findOneBy(['id' => $idNewName, 'owner' => $this->getUser()]);
            if($item === null)
                throw $this->createNotFoundException();
            if(!empty($item)){
                $item->setName($newName);
                $em->flush();
            }
        }

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
