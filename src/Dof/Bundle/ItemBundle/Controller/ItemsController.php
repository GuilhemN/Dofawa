<?php

namespace Dof\Bundle\ItemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use XN\Annotations as Utils;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;
use Dof\Bundle\ItemBundle\Form\ItemSearch;
use Dof\Bundle\ItemBundle\ItemSlot;
use Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter;
use Dof\Bundle\User\CharacterBundle\Entity\Stuff;
use Dof\Bundle\UserBundle\Entity\User;

class ItemsController extends Controller
{
    /**
     * @Utils\UsesSession
     */
    public function indexAction($page)
    {
        $form = $this->createForm(new ItemSearch(null, $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')));
        $form->handleRequest($this->get('request'));

        $params = $this->getItems($form->getData(), $page);

        return $this->render('DofItemBundle:Items:index.html.twig',
            ['form' => $form->createView()] + $params
            );
    }

    /**
     * @ParamConverter("item", options={"repository_method" = "findOneWithJoins"})
     */
    public function showAction(ItemTemplate $item)
    {
        return $this->render('DofItemBundle:Items:show.html.twig', ['item' => $item]);
    }

    /**
     * @Utils\UsesSession
     */
    public function showBuildItemsAction(Stuff $stuff, PlayerCharacter $character, User $user, $canWrite, $type, $slot, $page)
    {
        if (!$canWrite) {
            throw $this->createAccessDeniedException();
        }

        $repo = 'DofItemBundle:EquipmentTemplate';
        if ($type == 'animal') {
            $repo = 'DofItemBundle:AnimalTemplate';
        } elseif (($iSlot = ItemSlot::getValue(strtoupper($type))) === null) {
            throw $this->createNotFoundException('Type d\'item non trouvé');
        }
        $slugs = [
                    'user' => $user->getSlug(),
                    'character' => $character->getSlug(),
                    'stuff' => $stuff->getSlug(),
                ];

        $form = $this->createForm(new ItemSearch(false, $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')));
        $form->handleRequest($this->get('request'));
        $data = isset($iSlot) ? (array) $form->getData() + ['type' => $iSlot] : $form->getData();
        $params = $this->getItems($data, $page, $slugs + ['type' => $type, 'slot' => $slot], $repo);

        return $this->render('DofItemBundle:Items:index.html.twig',
            $params +
            [
                'form' => $form->createView(),
                'slugs' => $slugs,
                'slot_number' => $slot,
            ]
            );
    }

    protected function getItems($options, $page, array $params = array(), $repo = 'DofItemBundle:ItemTemplate')
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository($repo);
        $full = $this->get('security.context')->isGranted('ROLE_SPELL_XRAY');

        $perPage = 15;

        $locale = $this->get('translator')->getLocale();
        $count = $repo->countWithOptions($options, $locale);
        $items = $repo->findWithOptions($options, ['level' => 'DESC', 'name'.ucfirst($this->get('request')->getLocale()) => 'ASC'], $perPage, ($page - 1) * $perPage, $locale, 'normal', $full);

        $pagination = array(
            'page' => $page,
            'route' => $this->get('request')->attributes->get('_route'),
            'pages_count' => ceil($count / $perPage),
            'route_params' => $params,
        );

        return array(
            'count' => $count,
            'items' => $items,
            'pagination' => $pagination,
        );
    }
}
