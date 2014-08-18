<?php

namespace Dof\BuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\BuildBundle\Entity\Stuff;
use Dof\GraphicsBundle\Entity\BuildLook;

use Dof\CharactersBundle\Gender;

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
            $stuff->setName('1er Stuff');

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
        }

        return $this->render('DofBuildBundle:Build:index.html.twig', array('characters' => $characters, 'form' => $form->createView()));
    }

    public function showAction($user, $character, $stuff){
        $em = $this->getDoctrine()->getManager();
        $persoR = $em->getRepository('DofBuildBundle:PlayerCharacter');

        $perso = $persoR->findForShow($user, $character);
        if(empty($perso))
            throw $this->createNotFoundException();

        return $this->render('DofBuildBundle:Build:show.html.twig', ['perso' => $perso]);
    }
}
