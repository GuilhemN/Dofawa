<?php

namespace Dof\Bundle\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dof\Bundle\ItemBundle\ItemSlot;
use Dof\Bundle\GraphicsBundle\LivingItem;

class JsController extends Controller
{
    public function characterLookAction()
    {
        $locale = $this->get('request')->getLocale();

        $itemTemplate = $this->getDoctrine()->getManager()
            ->getRepository('DofItemBundle:ItemTemplate');
        $skinned = $this->getDoctrine()->getManager()
            ->getRepository('DofItemBundle:SkinnedEquipmentTemplate');
        $animal = $this->getDoctrine()->getManager()
            ->getRepository('DofItemBundle:AnimalTemplate');
        $weapon = $this->getDoctrine()->getManager()
            ->getRepository('DofItemBundle:WeaponTemplate');

        $chameleonDrago = $this->get('dof_graphics.chameleon_dragoturkey');

        // Récupération de tous les items
        $items['shield'] = $skinned->findBySlot(ItemSlot::SHIELD, $locale);
        $items['hat'] = $skinned->findBySlot(ItemSlot::HAT, $locale);
        $items['cloak'] = $skinned->findBySlot(ItemSlot::CLOAK, $locale);

        $items['animal'] = $animal->hasBone('json', $locale);
        $items['animal'][] = array('id' => $chameleonDrago->getId(), 'name' => $chameleonDrago->getName());

        $items['weapon'] = $weapon->hasSkin('json', $locale);

        // Récupération de tous les obvijevans
        $livingItem = $itemTemplate->findBySlot(ItemSlot::LIVING_ITEM, $locale);
        $typeLI = LivingItem::getTypes();

        foreach ($livingItem as $lItem) {
            if (isset($typeLI[$lItem['id']])) {
                $type = $typeLI[$lItem['id']];
                if ($type == 16) { // Coiffe
                    $this->rangeLItem($items['hat'], $lItem);
                } elseif ($type == 17) { // Cape
                    $this->rangeLItem($items['cloak'], $lItem);
                }
            }
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/javascript');

        return $this->render('DofGraphicsBundle:Js:characterLook.js.twig', [
            'items' => $items,
            'types' => array_keys($items),
        ],
        $response);
    }

    public function colorSlotsAction()
    {
        $translator = $this->get('translator');

        $translation = $translator->getCatalogue('color_slots');
        $translationFr = $translator->getCatalogue('color_slots', 'fr');

        $breeds = $this->getDoctrine()->getManager()
                        ->getRepository('DofCharacterBundle:Breed')
                        ->findRelation();

        foreach ($breeds as $v) {
            $nBreeds[$v['id']] = $v['slug'];
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/javascript');

        return $this->render('DofGraphicsBundle:Js:colorSlots.js.twig', [
            'translation' => $translation,
            'translation_fr' => $translationFr,
            'breeds' => $nBreeds,
        ],
        $response);
    }

    private function rangeLItem(array &$array, $item)
    {
        foreach (range(1, 20) as $i) {
            $array[] = array(
              'id' => $item['id'].'/'.$i,
              'name' => $item['name'].' '.$i,
            );
        }
    }
}
