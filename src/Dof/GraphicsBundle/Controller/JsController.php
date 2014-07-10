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

        $items['shield'] = $skinned->findBySlot(7);
        $items['hat']    = $skinned->findBySlot(10);
        $items['cloak']  = $skinned->findBySlot(11);
        $items['animal'] = $animal->hasBone();
        $items['weapon'] = $weapon->hasBone();

        foreach($items as $k => $v){
            foreach($v as $val){
                $nitems[$k] = array('id' => $val['id'], 'text' => $val['name_'.$locale]);
            }
        }

        return $this->render('DofGraphicsBundle:Js:characterLook.js.twig', [
            'items' => $nitems
        ]);
    }
}
