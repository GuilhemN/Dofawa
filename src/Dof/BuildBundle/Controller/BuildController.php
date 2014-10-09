<?php

namespace Dof\BuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\UserBundle\Entity\User;
use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\BuildBundle\Entity\Stuff;
use Dof\BuildBundle\Entity\Item;
use Dof\GraphicsBundle\Entity\BuildLook;

use Dof\BuildBundle\BuildSlot;
use Dof\CharactersBundle\Gender;

class BuildController extends Controller
{
    protected $dofus_slots = [
                'dofus1' => BuildSlot::DOFUS1,
                'dofus2' => BuildSlot::DOFUS2,
                'dofus3' => BuildSlot::DOFUS3,
                'dofus4' => BuildSlot::DOFUS4,
                'dofus5' => BuildSlot::DOFUS5,
                'dofus6' => BuildSlot::DOFUS6,
            ];
    protected $items_slots = [
                'hat' => BuildSlot::HAT,
                'cloak' => BuildSlot::CLOAK,
                'amulet' => BuildSlot::AMULET,
                'weapon' => BuildSlot::WEAPON,
                'ring1' => BuildSlot::RING1,
                'ring2' => BuildSlot::RING2,
                'belt' => BuildSlot::BELT,
                'boots' => BuildSlot::BOOTS,
                'shield' => BuildSlot::SHIELD,
                'animal' => BuildSlot::ANIMAL,
            ];

    public function indexAction()
    {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $characters = $em->getRepository('DofBuildBundle:PlayerCharacter')->findByUser($user);

        $form = $this->createForm('dof_buildbundle_playercharacter', new PlayerCharacter());
        $form->handleRequest($this->get('request'));

        if($form->isValid()){
            $faces = $em->getRepository('Dof\CharactersBundle\Entity\Face');

            $character = $form->getData();
            $stuff = new Stuff();
            $look = new BuildLook();

            $dform = $this->get('request')->request->get('dof_buildbundle_playercharacter');

            $breed = $character->getBreed();

            // Récupération du bon visage
            $face = $faces->findOneBy(array('breed' => $breed, 'label' => $dform['face'], 'gender' => $dform['gender']));

            // Apparence du 1er personnage
            $look->setColors($breed->{'get'.strtolower(ucfirst(Gender::getName($dform['gender']))).'DefaultColors'}());
            $look->setBreed($breed);
            $look->setFace($face);
            $look->setGender($dform['gender']);

            // Ajout d'un nom au premier stuff
            $stuff->setName('1er Stuff de '  . $character->getName());

            // Relations
            $character->addStuff($stuff);
            $stuff->setCharacter($character);

            $stuff->setLook($look);
            $look->setStuff($stuff);

            // Persistance
            $em->persist($character);
            $em->persist($stuff);
            $em->persist($look);

            $em->flush();

            // Badge
            $this->get('badge_manager')->addBadge('create-build');

            return $this->redirect($this->generateUrl('dof_build_show', array(
                'user' => $this->getUser()->getSlug(),
                'character' => $character->getSlug(),
                'stuff' => $stuff->getSlug()
                )));
        }

        return $this->render('DofBuildBundle:Build:index.html.twig', array('characters' => $characters, 'form' => $form->createView()));
    }

    public function showAction(Stuff $stuff, PlayerCharacter $character, $canSee, $canWrite){
        if(!$canSee)
            throw $this->createAccessDeniedException();

        $items = array();
        foreach($stuff->getItems() as $item){
            $items[$item->getSlot()] = $item;
        }

        return $this->render('DofBuildBundle:Build:show.html.twig', [
            'character' => $character,
            'stuff' => $stuff,
            'dofus_slots' => $this->dofus_slots,
            'items_slots' => $this->items_slots,
            'items' => $items,
            'can_write' => $canWrite
            ]);
    }

    public function addItemsAction(Stuff $stuff, PlayerCharacter $character, User $user, $canWrite){
        if(!$canWrite)
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $itemsIds = (array) $request->request->get('items');
        $rel = array_flip($itemsIds);
        $items = $em->getRepository('DofItemsBundle:ItemTemplate')->findById($itemsIds);

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

            $caracts = $bItem->getCharacteristics();
            foreach($caracts as $k => &$caract)
                $caract = $item->{'getMax' . ucfirst($k)}();

            $bItem->setCharacteristics($caracts, true);

            $em->persist($bItem);

        }
        $em->flush();

        $stuff = $em->getRepository('DofBuildBundle:stuff')->findOneBy($stuff->getId())->updatePrimaryBonus();
        $em->flush($stuff);

        return $this->redirect($this->generateUrl('dof_build_show', [
            'user' => $user->getSlug(),
            'character' => $character->getSlug(),
            'stuff' => $stuff->getSlug()
            ]));
    }
}
