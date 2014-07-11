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

        // Vérif et liage cape, coiffe et bouclier
        $skinnedItems = ['shield' => 7, 'hat' => 10, 'cloak' => 11];
        foreach($skinnedItems as $item => $slot){
            $item = $skinned->findById($form[$item]);

            if(!empty($item) && $item->getSlot() == $slot && $item->getSkin() > 0)
                $lg->{'set'.ucfirst($item)}($item);
        }

        // Liage Arme
        $item = $weapon->findById($form['weapon']);
        if(!empty($item) && $item->getSkin() > 0)
            $lg->setWeapon($item);

        // Liage Familier
        $item = $animal->findById($form['animal']);

        if(!empty($item) && $item->getBone() > 0)
            $lg->setAnimal($item);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cl);
        $em->flush();
    }
}
