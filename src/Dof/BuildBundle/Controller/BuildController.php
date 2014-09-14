<?php

namespace Dof\BuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\BuildBundle\Entity\Stuff;
use Dof\GraphicsBundle\Entity\BuildLook;

use Dof\BuildBundle\BuildSlot;
use Dof\CharactersBundle\Gender;
use Dof\UserBundle\Entity\Badge;

class BuildController extends Controller
{
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

    /**
     * @ParamConverter("stuff", class="DofBuildBundle:Stuff", options={"mapping": {"stuff" = "slug"} })
     */
    public function showAction($user, $character, Stuff $stuff){
        $em = $this->getDoctrine()->getManager();
        $persoR = $em->getRepository('DofBuildBundle:PlayerCharacter');

        $perso = $persoR->findForShow($user, $character);

        $items = array();
        foreach($stuff->getItems() as $item){
            $items[$item->getSlot()] = $item;
        }

        if(empty($perso))
            throw $this->createNotFoundException();

        return $this->render('DofBuildBundle:Build:show.html.twig', [
            'perso' => $perso,
            'stuff' => $stuff,
            'dofus_slots' => [
                BuildSlot::DOFUS1,
                BuildSlot::DOFUS2,
                BuildSlot::DOFUS3,
                BuildSlot::DOFUS4,
                BuildSlot::DOFUS5,
                BuildSlot::DOFUS6,
                ],
            'items_slots' => [
                BuildSlot::HAT,
                BuildSlot::CLOAK,
                BuildSlot::AMULET,
                BuildSlot::WEAPON,
                BuildSlot::RING1,
                BuildSlot::RING2,
                BuildSlot::BELT,
                BuildSlot::BOOTS,
                BuildSlot::SHIELD,
                ]
            ]);
    }
}
