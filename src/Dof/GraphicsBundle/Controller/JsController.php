<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JsController extends Controller
{
    public function characterLookAction()
    {
        $locale = $this->get('request')->getLocale();


        $skinned = $this->getDoctrine()->getEntityManager()
                        ->getRepository('DofItemBundle:SkinnedEquipment');
        $animal  = $this->getDoctrine()->getEntityManager()
                        ->getRepository('DofItemBundle:AnimalTemplate');
        $weapon  = $this->getDoctrine()->getEntityManager()
                        ->getRepository('DofItemBundle:WeaponTemplate');

        $items['shield'] = $repository->findBySlot(7);
        $items['hat']    = $skinned->findBySlot(10);
        $items['cloak']  = $skinned->findBySlot(11);
        $items['animal'] = $animal->hasBone('json', $locale);
        $items['weapon'] = $weapon->hasBone('json', $locale);

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
