<?php

namespace Dof\BuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\UserBundle\Entity\User;
use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\BuildBundle\Entity\Stuff;
use Dof\ItemsManagerBundle\Entity\PersonalizedItem;

use Dof\GraphicsBundle\Entity\BuildLook;
use Dof\ItemsBundle\ItemSlot;

class StuffController extends Controller
{
    public function addItemsAction(){
        $bm = $this->get('build_manager');
        $iFact = $this->get('item_factory');
        $request = $this->get('request');
        $stuffSlug = $request->request->get('stuff');

        $stuff = $bm->getStuffBySlug($stuffSlug);
        if($stuff === null)
            throw $this->createNotFoundException();

        if(!$bm->canWrite($stuff))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $itemsIds = (array) $request->request->get('items');
        if($request->request->has('percent'))
            $percent = intval($request->request->get('percent'));
        else
            $percent = 80;
        if($request->request->has('useSlot'))
            $useSlot = intval($request->request->get('useSlot'));
        else
            $useSlot = 0;

        $rel = array_flip($itemsIds);
        $items = $em->getRepository('DofItemsBundle:EquipmentTemplate')->findById($itemsIds);
        $look = $stuff->getLook();

        $bItemRepo = $em->getRepository('DofItemsManagerBundle:Item');
        foreach($items as $k => $item) {
            if($useSlot)
                $slot = round(intval($rel[$item->getId()]));
            else
                $slot = 0;

            $bItem = $iFact->createItem($item, null, $stuff->getCharacter()->getOwner());
            $type = $stuff->getItemType($bItem, $slot);
            $lItem = $stuff->{'get' . ucfirst($type)}();

            if(!empty($lItem) && $lItem->getName() == null && count($lItem->getStuffs()) <= 1){
                $em->remove($lItem);
                $em->flush();
            }

            $stuff->{'set' . ucfirst($type)}($bItem);
            $em->persist($bItem);
            $em->persist($stuff);

        }

        $em->flush();

        return $this->redirect($this->generateUrl('dof_build_show', [
            'user' => $stuff->getCharacter()->getOwner()->getSlug(),
            'character' => $stuff->getCharacter()->getSlug(),
            'stuff' => $stuff->getSlug()
            ]));
    }

    public function createStuffAction(Stuff $oldStuff){
        $bm = $this->get('build_manager');
        $em = $this->getDoctrine()->getManager();
        $name = $this->get('request')->request->get('name');

        if(!$bm->canWrite($oldStuff) or empty($name))
            throw $this->createAccessDeniedException();

        $stuff = new Stuff;
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
        return $this->redirect($this->generateUrl('dof_build_show', array(
            'user' => $character->getOwner()->getSlug(),
            'character' => $character->getSlug(),
            'stuff' => $stuff->getSlug()
        )));
    }
}
