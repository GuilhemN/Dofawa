<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Dof\GraphicsBundle\Entity\CharacterLook;
use Dof\ItemsBundle\ItemSlot;
use Dof\GraphicsBundle\LivingItem;

class CharacterLookController extends Controller
{
    public function createAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_STYLIST_BETA'))
            throw new AccessDeniedException();

        $cl = new CharacterLook();
        $cl->setColors(array(
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
        $form = $this->createForm('character_look', $cl);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $cl = $this->handler($request, $cl);

            return $this->redirect($this->getCLUrl($cl));
        }

        return $this->render('DofGraphicsBundle:CharacterLook:create.html.twig', ['form' => $form->createView()]);
    }

    protected function getCLUrl($cl){
        $this->generateUrl('dof_graphics_skins_embed', array('slug' => $cl->getSlug()));
    }

    protected function handler($request, $cl){
        if(!$this->get('security.context')->isGranted('ROLE_STYLIST'))
            $cl->setPubliclyVisible(0);

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
                  $cl->{'set'.ucfirst($name)}($livingItemFactory->createFromTemplateAndLevel($item[0], $params[1]));
              // Si item normal
              elseif($item[0]->getType()->getSlot() == constant('ItemSlot::' . $slotConst) && $item[0]->getSkin() > 0)
                  $cl->{'set'.ucfirst($name)}($item[0]);
            }
        }

        // Liage Arme
        $item = $weapon->findById($form['weapon']);
        if(!empty($item) && $item[0]->getSkin() > 0)
            $cl->setWeapon($item[0]);

        // Liage Familier
        if($form['animal'] == 'chameleon')
            $cl->setAnimal($chameleonDrago = $this->get('dof_graphics.chameleon_dragoturkey'));
        else{
            $item = $animal->findById($form['animal']);

            if(!empty($item) && $item[0]->getBone() > 0)
                $cl->setAnimal($item[0]);
        }

        // Couleurs en décimal
        $colors = $cl->getColors();
        foreach($colors as &$color){
          $color = hexdec($color); # TODO : créer une validation sur le formulaire + erreur
        }

        $cl->setColors($colors);

        // Recherche de la face
        $faceResult = $face->findForCharacterLook($cl->getBreed(), $cl->getGender(), $form['face']);
        $cl->setFace($faceResult[0]);

        // Sauvegarde en bdd
        $em = $this->getDoctrine()->getManager();
        $em->persist($cl);
        $em->flush();

        return $cl;
    }
}
