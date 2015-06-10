<?php

namespace Dof\Bundle\User\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dof\Bundle\UserBundle\Entity\User;
use Dof\Bundle\User\CharacterBundle\Entity\Stuff;
use Dof\Bundle\GraphicsBundle\Entity\BuildLook;

class StuffController extends Controller
{
    public function addItemsAction()
    {
        $bm = $this->get('build_manager');
        $iFact = $this->get('item_factory');
        $request = $this->get('request');

        $stuff = $bm->getStuffBySlug($request->request->get('stuff'));
        if ($stuff === null) {
            throw $this->createNotFoundException();
        }
        if (!$stuff->canWrite()) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $itemsIds = (array) $request->request->get('items');
        if ($request->request->has('percent')) {
            $percent = intval($request->request->get('percent'));
        } else {
            $percent = 85;
        }
        if ($request->request->has('useSlot')) {
            $useSlot = intval($request->request->get('useSlot'));
        } else {
            $useSlot = 0;
        }

        $rel = array_flip($itemsIds);
        $items = $em->getRepository('DofItemBundle:EquipmentTemplate')->findById($itemsIds);
        $look = $stuff->getLook();

        $bItemRepo = $em->getRepository('DofUserItemBundle:Item');
        foreach ($items as $k => $item) {
            if ($useSlot) {
                $slot = round(intval($rel[$item->getId()]));
            } else {
                $slot = 0;
            }

            $bItem = $iFact->createItem($item, $percent, $stuff->getCharacter()->getOwner());
            $bItem->setSticky(false);
            $type = $stuff->getItemType($bItem, $slot);
            if (!$type) {
                continue;
            }
            $lItem = $stuff->{'get'.ucfirst($type)}();

            if (!empty($lItem) && !$lItem->isSticky() && $lItem->getName() == null && count($lItem->getStuffs()) <= 1) {
                $em->remove($lItem);
                $em->flush();
            }

            $stuff->{'set'.ucfirst($type)}($bItem);
            $em->persist($bItem);
            $em->persist($stuff);
        }

        $em->flush();

        return $this->redirect($this->generateUrl('dof_user_character_stuff', [
            'user' => $stuff->getCharacter()->getOwner()->getSlug(),
            'character' => $stuff->getCharacter()->getSlug(),
            'stuff' => $stuff->getSlug(),
            ]));
    }

    public function createStuffAction(Stuff $oldStuff)
    {
        $bm = $this->get('build_manager');
        $em = $this->getDoctrine()->getManager();
        $name = $this->get('request')->request->get('name');

        if (!$oldStuff->canWrite() or empty($name)) {
            throw $this->createAccessDeniedException();
        }

        $stuff = new Stuff();
        $stuff->setName($name);
        $stuff->setFaceLabel($oldStuff->getFaceLabel());
        $stuff->setCharacter($oldStuff->getCharacter());
        $stuff->setVisible($oldStuff->getVisible());

        $stuff->setVitality($oldStuff->getVitality());
        $stuff->setWisdom($oldStuff->getWisdom());
        $stuff->setStrength($oldStuff->getStrength());
        $stuff->setIntelligence($oldStuff->getIntelligence());
        $stuff->setChance($oldStuff->getChance());
        $stuff->setAgility($oldStuff->getAgility());

        $oldLook = $oldStuff->getLook();
        $look = new BuildLook();
        $look->setGender($oldLook->getGender());
        $look->setColors($oldLook->getColors());

        $look->setStuff($stuff);
        $stuff->setLook($look);

        $stuff->setCharacter($oldStuff->getCharacter());

        $em->persist($look);
        $em->persist($stuff);

        $em->flush();

        $character = $stuff->getCharacter();

        return $this->redirect($this->generateUrl('dof_user_character_stuff', array(
            'user' => $character->getOwner()->getSlug(),
            'character' => $character->getSlug(),
            'stuff' => $stuff->getSlug(),
        )));
    }
}
