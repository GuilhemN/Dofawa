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

        $items['shield'] = $skinned->findBySlot(7, $locale);
        $items['hat']    = $skinned->findBySlot(10, $locale);
        $items['cloak']  = $skinned->findBySlot(11, $locale);
        $items['animal'] = $animal->hasBone('json', $locale);
        $items['weapon'] = $weapon->hasBone('json', $locale);

        print_r($items);
        die();
        
        foreach($items as $k => $v){
            foreach($v as $val){
                $nitems[$k] = array('id' => $val['id'], 'text' => $val['name'.ucfirst($locale)]);
            }
        }

        return $this->render('DofGraphicsBundle:Js:characterLook.js.twig', [
            'items' => $nitems
        ]);
    }
}
