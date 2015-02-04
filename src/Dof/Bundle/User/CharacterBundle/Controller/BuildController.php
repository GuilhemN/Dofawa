<?php

namespace Dof\Bundle\User\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use Dof\Bundle\UserBundle\Entity\User;
use Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter;
use Dof\Bundle\User\CharacterBundle\Entity\Stuff;
use Dof\Bundle\User\CharacterBundle\Entity\Item;
use Dof\Bundle\GraphicsBundle\Entity\BuildLook;
use Dof\Bundle\CharacterBundle\Entity\Breed;


use Dof\Bundle\User\CharacterBundle\BuildSlot;
use Dof\Bundle\CharacterBundle\Gender;

use Dof\Bundle\User\CharacterBundle\Form\ConfigurationForm;

use Dof\Bundle\ItemBundle\EffectListHelper;
use Dof\Bundle\CharacterBundle\RankDamageEffect;

use XN\UtilityBundle\ActionEvents;
use XN\UtilityBundle\Event\CreateActionEvent;

class BuildController extends Controller
{
    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     * @Utils\UsesSession
     */
    public function indexAction()
    {
        $bm = $this->get('build_manager');
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $characters = $em->getRepository('DofUserCharacterBundle:PlayerCharacter')->findByUser($user);
        foreach($characters as $character)
            $bm->transformStuff($character->getStuffs()[0]);
        $playerCharacter = new PlayerCharacter();

        // Si la demande d'ajout provient de la fiche de présentation d'une classe
        $newChar = $this->get('request')->get('newChar');
        if(!empty($newChar) && $newChar > 0 && $newChar < 17){
            $newBreed = $em->getRepository('DofCharacterBundle:Breed')->find($newChar);
            $playerCharacter->setBreed($newBreed);
        }

        $form = $this->createForm('dof_user_characterbundle_playercharacter', $playerCharacter);
        $form->handleRequest($this->get('request'));

        if($form->isValid()){
            $character = $form->getData();
            $stuff = new Stuff();
            $look = new BuildLook();
            $breed = $character->getBreed();

            $dform = $this->get('request')->request->get('dof_user_characterbundle_playercharacter');

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

            return $this->redirect($this->generateUrl('dof_user_character_show', array(
                'user' => $this->getUser()->getSlug(),
                'character' => $character->getSlug(),
                'stuff' => $stuff->getSlug()
                )));
        }

        return $this->render('DofUserCharacterBundle:Build:index.html.twig', array('characters' => $characters, 'form' => $form->createView()));
    }

    public function showAction($user, Stuff $stuff, PlayerCharacter $character, $canSee, $canWrite){
        if(!$canSee) // Si n'a pas le droit de voir ce build
            throw $this->createAccessDeniedException();

        $event = new CreateActionEvent('build', $stuff);
        $this->get('event_dispatcher')->dispatch(ActionEvents::CREATE, $event);

        $bm = $this->get('build_manager');
        $items = $stuff->getItems();

        //Modification d'un item
        $request = $this->get('request');
        if ($canWrite && $request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $itemId = $request->request->get('id');
            foreach($items as $v)
                foreach($v as $i)
                    if($i !== null && $i->getId() == $itemId){
                        $i->setCharacteristics($request->request->get('caracts'), true);

                        $em->persist($i);
                        $em->flush($i);
                        break 2;
                    }
        }

        $characteristics = $bm->getCharacteristics($stuff, $bonus);

        return $this->render('DofUserCharacterBundle:Build:show.html.twig', [
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

    /**
     * @Utils\UsesSession
     */
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

        return $this->render('DofUserCharacterBundle:Build:configuration.html.twig', [
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

        return $this->render('DofUserCharacterBundle:Build:showCharacteristics.html.twig', [
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

        $weapon = $stuff->getWeapon();

        return $this->render('DofUserCharacterBundle:Build:showWeaponDamages.html.twig', [
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

        return $this->render('DofUserCharacterBundle:Build:showSpellsDamages.html.twig', [
            'characteristics' => $characteristics,
            'character' => $character,
            'stuff' => $stuff,
            'user' => $user,
            'can_write' => $canWrite,
            ]);
    }

}
