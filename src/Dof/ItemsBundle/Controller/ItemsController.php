<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemTemplate;
use Dof\ItemsBundle\Form\ItemSearch;

use Dof\ItemsBundle\ItemSlot;
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

    public function showBuildItemsAction(Stuff $stuff, PlayerCharacter $character, User $user, $canWrite, $type, $slot, $page){
        if(!$canWrite)
            throw $this->createAccessDeniedException();
        $slotNumber = $slot;

        $repo = 'DofItemsBundle:EquipmentTemplate';
        if($type == 'animal')
            $repo = 'DofItemsBundle:AnimalTemplate';
        elseif(($slot = ItemSlot::getValue(strtoupper($type))) === null)
            throw $this->createNotFoundException('Type d\'item non trouvé');
        $slugs = [
                    'user' => $user->getSlug(),
                    'character' => $character->getSlug(),
                    'stuff' => $stuff->getSlug()
                ];

        $form = $this->createForm(new ItemSearch(false));
        $form->handleRequest($this->get('request'));
        $data = isset($slot) ? array_merge($form->getData(), ['type' => $slot]) : $form->getData();
        $params = $this->getItems($form->getData(), $page, $slugs + ['type' => $type], $repo);

        return $this->render('DofItemsBundle:Items:index.html.twig',
            $params +
            [
                'form' => $form->createView(),
                'slugs' => $slugs,
                'slot_number' => $slotNumber
            ]
            );
    }

    protected function getItems($options, $page, array $params = array(), $repo = 'DofItemsBundle:ItemTemplate') {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository($repo);
        $full = $this->get('security.context')->isGranted('ROLE_SPELL_XRAY');

        $perPage = 15;

        $locale = $this->get('translator')->getLocale();
        $count = $repo->countWithOptions($options, $locale);
        $items = $repo->findWithOptions($options, ['level' => 'DESC', 'name' . ucfirst($this->get('request')->getLocale()) => 'ASC'], $perPage, ($page - 1) * $perPage, $locale, 'normal', $full);

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
