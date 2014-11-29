<?php

namespace Dof\BuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\UserBundle\Entity\User;
use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\BuildBundle\Entity\Stuff;
use Dof\BuildBundle\Entity\Item;

use Dof\GraphicsBundle\Entity\BuildLook;

use Dof\BuildBundle\BuildSlot;

class StuffController extends Controller
{
    public function addItemsAction(Stuff $stuff, PlayerCharacter $character, User $user, $canWrite){
        if(!$canWrite)
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $itemsIds = (array) $request->request->get('items');
        $rel = array_flip($itemsIds);
        $items = $em->getRepository('DofItemsBundle:ItemTemplate')->findById($itemsIds);
        $look = $stuff->getLook();

        $bItemRepo = $em->getRepository('DofBuildBundle:Item');
        foreach($items as $k => $item) {
            if(($slot = BuildSlot::getValue(strtoupper($rel[$item->getId()]))) === null)
                $slot = BuildSlot::getBuildSlot($item->getType()->getSlot())[0];

            $bItem = $bItemRepo->findOneBy(array('stuff' => $stuff, 'slot' => $slot));
            if($bItem !== null)
                $em->remove($bItem);

            $bItem = new Item();
            $bItem->setStuff($stuff);
            $bItem->setItemTemplate($item);
            $bItem->setSlot($slot);

            if($slot == BuildSlot::WEAPON)
                $look->setWeapon($item);
            elseif($slot == BuildSlot::SHIELD)
                $look->setShield($item);
            elseif($slot == BuildSlot::HAT)
                $look->setHat($item);
            elseif($slot == BuildSlot::CLOAK)
                $look->setCloak($item);
            elseif($slot == BuildSlot::ANIMAL)
                $look->setAnimal($item);

            $caracts = $bItem->getCharacteristics();
            foreach($caracts as $k => &$caract)
                $caract = $item->{'getMax' . ucfirst($k)}();

            $bItem->setCharacteristics($caracts, true);

            $em->persist($bItem);

        }
        $em->flush();

        $stuff = $em->getRepository('DofBuildBundle:stuff')->findOneBy(array('id' => $stuff->getId()));
        $stuff->updatePrimaryBonus();
        $em->flush($stuff);

        return $this->redirect($this->generateUrl('dof_build_show', [
            'user' => $user->getSlug(),
            'character' => $character->getSlug(),
            'stuff' => $stuff->getSlug()
            ]));
    }

    public function createStuff(Stuff $oldStuff){
        $bm = $this->get('build_manager');
        $em = $this->getDoctrine()->getManager();
        $name = $this->get('request')->request->get('name');

        if(!$bm->canWrite($oldStuff) or empty($name))
            throw $this->createAccessDeniedException();

        $stuff = clone $oldStuff;
        $stuff->setName($name);
        $em->persist($stuff->getLook());
        $em->persist($stuff);

        $em->flush();

        return $this->redirect($this->generateUrl('dof_build_show', array(
            'user' => $character->getUser()->getSlug(),
            'character' => $character->getSlug(),
            'stuff' => $stuff->getSlug()
        )));
    }
}
