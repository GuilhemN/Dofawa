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

use Dof\BuildBundle\Form\ConfigurationForm;

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
            $character->setVisible(true);
            $stuff->setCharacter($character);

            $stuff->setLook($look);
            $stuff->setVisible(true);
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

    public function showAction($user, Stuff $stuff, PlayerCharacter $character, $canSee, $canWrite){
        if(!$canSee) // Si n'a pas le droit de voir ce build
            throw $this->createAccessDeniedException();

        $bm = $this->get('build_manager');
        $items = [];

        // Pré-traitement des items
        foreach($stuff->getItems() as $item)
            $items[$item->getSlot()] = $item;
        $characteristics = $bm->getCharacteristics($stuff, $bonus);

        //Modification d'un item
        $request = $this->get('request');
        if ($canWrite && $request->isMethod('POST') && isset($items[$request->request->get('slot')]))
        {
            $em = $this->getDoctrine()->getManager();
            $item = $items[$request->request->get('slot')];
            $item->setCharacteristics($request->request->get('caracts'), true);

            $em->persist($item);
            $em->flush($item);
        }

        return $this->render('DofBuildBundle:Build:show.html.twig', [
            // Perso
            'character' => $character,
            'stuff' => $stuff,
            // Items
            'dofus_slots' => $this->dofus_slots,
            'items_slots' => $this->items_slots,
            'items' => $items,
            'bonus' => $bonus,
            'characteristics' => $characteristics,
            // Permissions
            'can_write' => $canWrite,
            'user' => $user
            ]);
    }

    public function configurationAction($user, Stuff $stuff, PlayerCharacter $character, $canWrite){
        if(!$canWrite) // Si n'a pas le droit de modifier ce build
            throw $this->createAccessDeniedException();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ConfigurationForm ());
        $form->handleRequest($this->get('request'));

        if($form->isValid()){
            $data = $form->getData();
            if(!empty($data['title']))
                $stuff->setName($data['title']);/*
            $stuff->setVitality($stuffData['vitality']);
            $stuff->setWisdom($stuffData['wisdom']);
            $stuff->setStrength($stuffData['strength']);
            $stuff->setIntelligence($stuffData['intelligence']);
            $stuff->setChance($stuffData['chance']);
            $stuff->setAgility($stuffData['agility']);*/
            $stuff->setVisible($data['stuffVisibility']);

            $characterData = $request->request->get('character');
            if(!empty($data['name']))
                $character->setName($data['name']);
            $character->setLevel($data['level']);
            $character->setVisible($data['characterVisibility']);

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('DofBuildBundle:Build:configuration.html.twig', [
            'breeds' => $breedR->findAll(),
            'character' => $character,
            'stuff' => $stuff,
            'user' => $user,
            'can_write' => $canWrite,
            ]);
    }

    public function showCharacteristicsAction($user, Stuff $stuff, PlayerCharacter $character, $canSee, $canWrite){
        if(!$canSee) // Si n'a pas le droit de voir ce build
            throw $this->createAccessDeniedException();

        $bm = $this->get('build_manager');
        $characteristics = $bm->getCharacteristics($stuff, $bonus);

        return $this->render('DofBuildBundle:Build:showCharacteristics.html.twig', [
            'characteristics' => $characteristics,
            'character' => $character,
            'stuff' => $stuff,
            'user' => $user,
            'can_write' => $canWrite,
            ]);
    }
}
