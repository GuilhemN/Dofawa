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
            $this->handler($form, $cl)
        }

        return $this->render('DofGraphicsBundle:CharacterLook:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    protected function handler($form, $cl){
        if(!$this->get('security.context')->isGranted('ROLE_STYLIST'))
            $cl->setPubliclyVisible(0);

        $data = $form->getData();

        $skinned = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:SkinnedEquipmentTemplate');
        $animal  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:AnimalTemplate');
        $weapon  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:WeaponTemplate');

        // Vérif et liage cape, coiffe et bouclier
        $skinnedItems = ['shield' => 7, 'hat' => 10, 'cloak' => 11];
        foreach($skinnedItems as $item => $slot){
            $id = (int) $data[$item];
            $item = $skinned->findById($id);

            if(!empty($item) && $item->getSlot() == $slot && !empty($item->getSkin()))
                $lg->{'set'.ucfirst($item)}($item);
        }

        // Liage Arme
        $item = $weapon->findById($data['weapon']);
        if(!empty($item) && !empty($item->getSkin()))
            $lg->setWeapon($item);

        // Liage Familier
        $item = $animal->findById($data['animal']);
        if(!empty($item) && !empty($item->getBone()))
            $lg->setAnimal($item);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cl);
        $em->flush();
    }
}
