<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JsController extends Controller
{
    public function characterLookAction()
    {
        $locale = $this->get('request')->getLocale();

        $skinned = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:SkinnedEquipmentTemplate');
        $animal  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:AnimalTemplate');
        $weapon  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:WeaponTemplate');

        $shield = $skinned->findBySlot(7, $locale);
        $hat    = $skinned->findBySlot(10, $locale);
        $cloak  = $skinned->findBySlot(11, $locale);
        $animal = $animal->hasBone('json', $locale);
        $weapons = $weapon->hasBone('json', $locale);

        foreach($weapons as $weapon){
          $nWeapons[] = ['id' => $weapon['id'], 'text' => $weapon['name'.ucfirst($locale)]];
        }

        return $this->render('DofGraphicsBundle:Js:characterLook.js.twig', [
            'weapons' => json_encode($nWeapons)
        ]);
    }
}
