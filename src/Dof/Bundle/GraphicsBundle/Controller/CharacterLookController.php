<?php

namespace Dof\Bundle\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use XN\Annotations as Utils;
use Dof\Bundle\GraphicsBundle\Entity\CharacterLook;
use Dof\Bundle\ItemBundle\ItemSlot;
use Dof\Bundle\GraphicsBundle\LivingItem;

class CharacterLookController extends Controller
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function listAction($page)
    {
        $repository = $this->getDoctrine()->getRepository('DofGraphicsBundle:CharacterLook');
        $countLooks = $repository->countTotal(true);

        // 15 results per page
        $looksPerPage = 15;
        $firstResult = ($page - 1) * $looksPerPage;

        if ($firstResult > $countLooks) {
            throw $this->createNotFoundException('This page does not exist.');
        }

        $looks = $repository->findLooks($firstResult, $looksPerPage);

        $pagination = array(
            'page' => $page,
            'route' => 'dof_graphics_skins_list',
            'pages_count' => ceil($countLooks / $looksPerPage),
            'route_params' => array(),
        );

        return $this->render('DofGraphicsBundle:CharacterLook:list.html.twig', array(
            'looks' => $looks,
            'page' => $page,
            'pagination' => $pagination,
        ));
    }

    /**
     * @Utils\UsesSession
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_STYLIST_BETA');

        $look = new CharacterLook();
        $look->setColors(array(
          '1' => '',
          '2' => '',
          '3' => '',
          '4' => '',
          '5' => '',
          '6' => '',
          '7' => '',
          '8' => '',
          '9' => '',
          '10' => '',
        ));
        $form = $this->createForm('character_look', $look);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $look = $this->handler($request, $look);

            return $this->redirect($this->getCLUrl($look));
        }

        return $this->render('DofGraphicsBundle:CharacterLook:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Utils\UsesSession
     */
    public function editAction(Request $request, CharacterLook $look)
    {
        $this->denyAccessUnlessGranted('ROLE_STYLIST_BETA');

        if (!$this->authorizationChecker->isGranted('ROLE_STYLIST_ADMIN') and $look->getCreator() != $this->authorizationChecker->getToken()->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $colors = $look->getColors();
        foreach ($colors as $k => &$color) {
            $color = str_pad(dechex($color), 6, '0', STR_PAD_LEFT);
        }

        $look->setColors($colors);

        $form = $this->createForm('character_look', $look);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $look = $this->handler($request, $look);

            return $this->redirect($this->getCLUrl($look));
        }

        return $this->render('DofGraphicsBundle:CharacterLook:edit.html.twig', ['form' => $form->createView()]);
    }

    protected function getCLUrl($look)
    {
        return $this->generateUrl('dof_graphics_skins_embed', array('slug' => $look->getSlug()));
    }

    protected function handler($request, $look)
    {
        if (!$this->authorizationChecker->isGranted('ROLE_STYLIST')) {
            $look->setPubliclyVisible(0);
        }

        $form = $request->request->get('character_look');

        $itemTemplate = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemBundle:ItemTemplate');
        $animal = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemBundle:AnimalTemplate');
        $weapon = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemBundle:WeaponTemplate');
        $face = $this->getDoctrine()->getManager()
                        ->getRepository('DofCharacterBundle:Face');
        $livingItemFactory = $this->get('dof_graphics.living_item_factory');

        // Vérif et liage cape, coiffe et bouclier
        $skinnedItems = ['shield', 'hat', 'cloak'];
        $typeLI = LivingItem::getTypes();

        foreach ($skinnedItems as $name) {
            $params = explode('/', $form[$name]);
            $slotConst = strtoupper($name);

            // Récupération en bdd de l'item
            $item = $itemTemplate->findByIdWithType($params[0]);

            // Si l'item existe
            if (!empty($item[0])) {
                $cuType = $typeLI[$item[0]->getId()];

              // Si Obvijevans
              if (!empty($params[1]) && !empty($cuType) && $item[0]->getType()->getSlot() == ItemSlot::LIVING_ITEM) {
                  $look->{'set'.ucfirst($name)}($livingItemFactory->createFromTemplateAndLevel($item[0], $params[1]));
              }
              // Si item normal
              elseif ($item[0]->getType()->getSlot() ==  ItemSlot::getValue($slotConst) && $item[0]->getSkin() > 0) {
                  $look->{'set'.ucfirst($name)}($item[0]);
              }
            }
        }

        // Liage Arme
        $item = $weapon->findById($form['weapon']);
        if (!empty($item) && $item[0]->getSkin() > 0) {
            $look->setWeapon($item[0]);
        }

        // Liage Familier
        $chameleonDrago = $this->get('dof_graphics.chameleon_dragoturkey');
        if ($form['animal'] == $chameleonDrago->getId()) {
            $look->setAnimal($chameleonDrago);
        } else {
            $item = $animal->findById($form['animal']);

            if (!empty($item) && $item[0]->getBone() > 0) {
                $look->setAnimal($item[0]);
            }
        }

        // Couleurs en décimal
        $colors = $look->getColors();
        foreach ($colors as &$color) {
            $color = hexdec($color); # TODO : créer une validation sur le formulaire + erreur
        }

        $look->setColors($colors);

        // Recherche de la face
        $faceResult = $face->findForCharacterLook($look->getBreed(), $look->getGender(), $form['face']);
        $look->setFace($faceResult[0]);

        // Sauvegarde en bdd
        $em = $this->getDoctrine()->getManager();
        $em->persist($look);
        $em->flush();

        return $look;
    }
}
