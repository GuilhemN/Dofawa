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

use Dof\ItemsBundle\EffectListHelper;
use Dof\CharactersBundle\RankDamageEffect;

class BuildController extends Controller
{
    public function indexAction()
    {
        $bm = $this->get('build_manager');
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $characters = $em->getRepository('DofBuildBundle:PlayerCharacter')->findByUser($user);
        foreach($characters as $character){
            $bm->transformStuff($character->getStuffs()[0]);
        }

        $form = $this->createForm('dof_buildbundle_playercharacter', new PlayerCharacter());
        $form->handleRequest($this->get('request'));

        if($form->isValid()){
            $character = $form->getData();
            $stuff = new Stuff();
            $look = new BuildLook();
            $breed = $character->getBreed();

            $dform = $this->get('request')->request->get('dof_buildbundle_playercharacter');

            // Apparence du 1er personnage
            $look->setColors($breed->{'get'.strtolower(ucfirst(Gender::getName($dform['gender']))).'DefaultColors'}());
            $look->setGender($dform['gender']);

            // Ajout d'un nom au premier stuff
            $stuff->setName('1er Stuff de '  . $character->getName());

            // Relations
            $character->addStuff($stuff);
            $character->setVisible(true);
            $stuff->setCharacter($character);

            $stuff->setLook($look);
            $stuff->setVisible(true);
            $stuff->setFaceLabel($dform['face']);
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
        $items = $stuff->getItems();

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

        $look = $stuff->getLook();

        $form = $this->createForm(new ConfigurationForm ($this->get('translator')->getLocale()), [
            'title' => $stuff->getName(),
            'stuffVisibility' => $stuff->getVisible(),
            'vitality' => $stuff->getVitality(),
            'wisdom' => $stuff->getWisdom(),
            'strength' => $stuff->getStrength(),
            'intelligence' => $stuff->getIntelligence(),
            'chance' => $stuff->getChance(),
            'agility' => $stuff->getAgility(),

            'name' => $character->getName(),
            'characterVisibility' => $character->getVisible(),
            'level' => $character->getLevel(),
            'breed' => $character->getBreed(),
            'gender' => $look->getGender(),
            'face' => $stuff->getFaceLabel()
        ]);
        $form->handleRequest($this->get('request'));

        if($form->isValid()){
            $bm = $this->get('build_manager');
            $data = $form->getData();
            if(!empty($data['title']))
                $stuff->setName($data['title']);

            $stuff->setVitality($data['vitality']);
            $stuff->setWisdom($data['wisdom']);
            $stuff->setStrength($data['strength']);
            $stuff->setIntelligence($data['intelligence']);
            $stuff->setChance($data['chance']);
            $stuff->setAgility($data['agility']);
            $stuff->setVisible($data['stuffVisibility']);
            $stuff->setFaceLabel($data['face']);

            if(!empty($data['name']))
                $character->setName($data['name']);
            $character->setLevel($data['level']);
            $character->setVisible($data['characterVisibility']);
            $character->setBreed($data['breed']);

            $look->setGender($data['gender']);

            $this->getDoctrine()->getManager()->flush();
            $stuff = $bm->reloadStuff($stuff);
        }

        return $this->render('DofBuildBundle:Build:configuration.html.twig', [
            'character' => $stuff->getCharacter(),
            'stuff' => $stuff,
            'user' => $user,
            'can_write' => $canWrite,
            'form' => $form->createView()
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

    public function showWeaponDamagesAction($user, Stuff $stuff, PlayerCharacter $character, $canSee, $canWrite){
        if(!$canSee) // Si n'a pas le droit de voir ce build
            throw $this->createAccessDeniedException();

        $bm = $this->get('build_manager');
        $characteristics = $bm->getCharacteristics($stuff, $bonus);

        foreach($stuff->getItems() as $item){
            if($item->getSlot() == 11){
                $weapon = $item;
                break;
            }
        }
        if(!isset($weapon))
            $weapon = null;

        return $this->render('DofBuildBundle:Build:showWeaponDamages.html.twig', [
            'characteristics' => $characteristics,
            'character' => $character,
            'weapon' => $weapon,
            'stuff' => $stuff,
            'user' => $user,
            'can_write' => $canWrite,
            ]);
    }

    public function showSpellsDamagesAction($user, Stuff $stuff, PlayerCharacter $character, $canSee, $canWrite){
        if(!$canSee) // Si n'a pas le droit de voir ce build
            throw $this->createAccessDeniedException();
        $bm = $this->get('build_manager');
        $characteristics = $bm->getCharacteristics($stuff, $bonus);

        foreach($character->getBreed()->getSpells() as $spell)
            foreach($spell->getRanks() as $rank){
                $de = $rank->getEffectsForDamage();
                $dmes = [];
                foreach($de as $effect){
                    $row = new RankDamageEffect($effect);
                    $row->applyCharateristics($characteristics);
                    $dmes[] = $row;
                }

                $rank->setDamageEffects($dmes);
            }

        return $this->render('DofBuildBundle:Build:showSpellsDamages.html.twig', [
            'characteristics' => $characteristics,
            'character' => $character,
            'stuff' => $stuff,
            'user' => $user,
            'can_write' => $canWrite,
            ]);
    }

}
