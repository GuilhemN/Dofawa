<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dof\ItemsBundle\ItemSlot;

use Dof\GraphicsBundle\LivingItem;

class JsController extends Controller
{
    public function characterLookAction()
    {
        $locale = $this->get('request')->getLocale();

        $itemTemplate = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:ItemTemplate');
        $skinned = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:SkinnedEquipmentTemplate');
        $animal  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:AnimalTemplate');
        $weapon  = $this->getDoctrine()->getManager()
                        ->getRepository('DofItemsBundle:WeaponTemplate');

        $chameleonDrago = $this->get('dof_graphics.chameleon_dragoturkey');

        // Récupération de tous les items
        $items['shield']   = $skinned->findBySlot(ItemSlot::SHIELD, $locale);
        $items['hat']      = $skinned->findBySlot(ItemSlot::HAT, $locale);
        $items['cloak']    = $skinned->findBySlot(ItemSlot::CLOAK, $locale);

        $items['animal']   = $animal->hasBone('json', $locale);
        $items['animal'][] = array('id' => 'chameleon', 'name' => $chameleonDrago->getName());

        $items['weapon'] = $weapon->hasSkin('json', $locale);

        // Récupération de tous les obvijevans
        $livingItem = $itemTemplate->findBySlot(ItemSlot::LIVING_ITEM, $locale);
        $typeLI = LivingItem::getTypes();

        foreach($livingItem as $lItem){
            $type = $typeLI[$lItem['id']];
            if(!empty($type))
                if($type == 16) // Coiffe
                    $this->rangeLItem($item['hat'], $lItem);
                elseif($type == 17) // Cape
                    $this->rangeLItem($item['cloak'], $lItem);
        }

        return $this->render('DofGraphicsBundle:Js:characterLook.js.twig', [
            'items' => $items,
            'types' => array_keys($items)
        ]);
    }

    private function rangeLItem(array &$array, $item){
        foreach (range(1, 20) as $i) {
            $array[] = array(
              'id' => $item['id'] . '/' . $i,
              'name' => $item['name'] . ' ' . $i
            );
        }

    }
}
