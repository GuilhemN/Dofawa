<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\GraphicsBundle\Entity\CharacterLook;
use Dof\ItemsBundle\ItemSlot;
use Dof\GraphicsBundle\LivingItem;

class CharacterLookController extends Controller
{
    public function createAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_STYLIST_BETA'))
            throw new AccessDeniedException();

        $look = new CharacterLook();
        $look->setColors(array(
          '1'=>'',
          '2'=>'',
          '3'=>'',
          '4'=>'',
          '5'=>'',
          '6'=>'',
          '7'=>'',
          '8'=>'',
          '9'=>'',
          '10'=>''
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
    * @ParamConverter("CharacterLook", options={"mapping": {"slug": "slug"}})
    */
    public function editAction(Request $request, CharacterLook $look)
    {
        if (!$this->get('security.context')->isGranted('ROLE_STYLIST_BETA'))
            throw new AccessDeniedException();
        $colors = $look->getColors();

        foreach($colors as &$color)
            $color = str_pad(dechex($color), 6, '0', STR_PAD_LEFT);

        $look->setColors($colors);

        $form = $this->createForm('character_look', $look);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $look = $this->handler($request, $look);

            return $this->redirect($this->getCLUrl($look));
        }

        return $this->render('DofGraphicsBundle:CharacterLook:create.html.twig', ['form' => $form->createView()]);
    }

    protected function getCLUrl($look){
        return $this->generateUrl('dof_graphics_skins_embed', array('slug' => $look->getSlug()));
    }

    protected function handler($request, $look){
        if(!$this->get('security.context')->isGranted('ROLE_STYLIST'))
            $look->setPubliclyVisible(0);

        $form = $request->request->get('character_look');

        $itemTemplate = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:ItemTemplate');
        $animal  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:AnimalTemplate');
        $weapon  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:WeaponTemplate');
        $face  = $this->getDoctrine()->getManager()
                        ->getRepository('DofCharactersBundle:Face');
        $livingItemFactory = $this->get('dof_graphics.living_item_factory');

        // Vérif et liage cape, coiffe et bouclier
        $skinnedItems = ['shield', 'hat', 'cloak'];
        $typeLI = LivingItem::getTypes();

        foreach($skinnedItems as $name){
            $params = explode('/', $form[$name]);
            $slotConst = strtoupper($name);

            // Récupération en bdd de l'item
            $item = $itemTemplate->findByIdWithType($params[0]);

            // Si l'item existe
            if(!empty($item[0])){
              $cuType = $typeLI[$item[0]->getId()];

              // Si Obvijevans
              if(!empty($params[1]) && !empty($cuType) && $item[0]->getType()->getSlot() == ItemSlot::LIVING_ITEM)
                  $look->{'set'.ucfirst($name)}($livingItemFactory->createFromTemplateAndLevel($item[0], $params[1]));
              // Si item normal
              elseif($item[0]->getType()->getSlot() == constant('ItemSlot::' . $slotConst) && $item[0]->getSkin() > 0)
                  $look->{'set'.ucfirst($name)}($item[0]);
            }
        }

        // Liage Arme
        $item = $weapon->findById($form['weapon']);
        if(!empty($item) && $item[0]->getSkin() > 0)
            $look->setWeapon($item[0]);

        // Liage Familier
        if($form['animal'] == 'chameleon')
            $look->setAnimal($chameleonDrago = $this->get('dof_graphics.chameleon_dragoturkey'));
        else{
            $item = $animal->findById($form['animal']);

            if(!empty($item) && $item[0]->getBone() > 0)
                $look->setAnimal($item[0]);
        }

        // Couleurs en décimal
        $colors = $look->getColors();
        foreach($colors as &$color){
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
