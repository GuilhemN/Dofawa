<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemTemplate;
use Dof\ItemsBundle\Form\ItemSearch;

use Dof\BuildBundle\BuildSlot;
use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\BuildBundle\Entity\Stuff;
use Dof\UserBundle\Entity\User;

class ItemsController extends Controller
{
    public function indexAction($page) {
        $form = $this->createForm(new ItemSearch());
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

    public function showBuildItemsAction(Stuff $stuff, PlayerCharacter $character, User $user, $canWrite, $type, $page){
        if(!$canWrite)
            throw $this->createAccessDeniedException();

        if(($buildSlot = BuildSlot::getValue(strtoupper($type))) === null)
            throw $this->createNotFoundException('Type d\'item non trouvé');
        $slugs = [
                    'user' => $user->getSlug(),
                    'character' => $character->getSlug(),
                    'stuff' => $stuff->getSlug()
                ];

        $form = $this->createForm(new ItemSearch(false));
        $form->handleRequest($this->get('request'));
        $params = $this->getItems(array_merge($form->getData(), ['type' => BuildSlot::getItemsSlot($buildSlot)]), $page, $slugs + ['type' => $type]);

        return $this->render('DofItemsBundle:Items:index.html.twig',
            $params +
            [
                'slugs' => $slugs,
                'build_slot' => $type
            ]
            );
    }

    protected function getItems($options, $page, array $params = array()) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemTemplate');

        $perPage = 15;

        $count = $repo->countWithOptions($options);
        $items = $repo->findWithOptions($options, ['level' => 'DESC', 'name' . ucfirst($this->get('request')->getLocale()) => 'ASC'], $perPage, ($page - 1) * $perPage);

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
