<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Dof\GraphicsBundle\Entity\CharacterLook;

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
            $this->handler($request, $cl);
        }

        return $this->render('DofGraphicsBundle:CharacterLook:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    protected function handler($request, $cl){
        if(!$this->get('security.context')->isGranted('ROLE_STYLIST'))
            $cl->setPubliclyVisible(0);

        $form = $request->request->get('character_look');

        $skinned = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:SkinnedEquipmentTemplate');
        $animal  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:AnimalTemplate');
        $weapon  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:WeaponTemplate');
        $face  = $this->getDoctrine()->getManager()
                        ->getRepository('DofCharactersBundle:Face');

        // Vérif et liage cape, coiffe et bouclier
        $skinnedItems = ['shield' => 7, 'hat' => 10, 'cloak' => 11];
        foreach($skinnedItems as $name => $slot){
            $item = $skinned->findById($form[$name]);

            if(!empty($item) && $item[0]->getType()->getSlot() == $slot && $item[0]->getSkin() > 0)
                $cl->{'set'.ucfirst($name)}($item[0]);
        }

        // Liage Arme
        $item = $weapon->findById($form['weapon']);
        if(!empty($item) && $item[0]->getSkin() > 0)
            $cl->setWeapon($item[0]);

        // Liage Familier
        $item = $animal->findById($form['animal']);

        if(!empty($item) && $item[0]->getBone() > 0)
            $cl->setAnimal($item[0]);

        // Couleurs en décimal
        $colors = $cl->getColors();
        foreach($colors as &$color){
          $color = hexdec($color); # TODO : créer une validation sur le formulaire + erreur
        }

        $cl->setColors($colors);

        // Recherche de la face
        $face = $face->findForCharacterLook($cl->getBreed(), $cl->getGender(), $form['face'])
        $cl->setFace($face);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cl);
        $em->flush();
    }
}
