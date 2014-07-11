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

        $shields = $skinned->findBySlot(7, $locale);
        $hats    = $skinned->findBySlot(10, $locale);
        $cloaks  = $skinned->findBySlot(11, $locale);
        $animals = $animal->hasBone('json', $locale);
        $weapons = $weapon->hasBone('json', $locale);

        $typeItems = array('shields', 'hats', 'cloaks', 'animals', 'weapons');

        foreach($typeItems as $type){
            $$type = (array) array();
            $fitems[$type] = array();
            foreach($$type as $singular)
              $fitems[$type][] = ['id' => $singular['id'], 'text' => $singular['name'.ucfirst($locale)]];

            $items[$type] = json_encode($fitems[$type], true);
        }

        return $this->render('DofGraphicsBundle:Js:characterLook.js.twig', [
            'items' => $items
        ]);
    }
}
