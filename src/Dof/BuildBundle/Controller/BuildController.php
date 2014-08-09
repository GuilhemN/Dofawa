<?php

namespace Dof\BuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\BuildBundle\Form\PlayerCharacterType;
use Dof\BuildBundle\Entity\PlayerCharacter;

class BuildController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        if(!empty($user))
            $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $characters = $em->getRepository('DofBuildBundle:PlayerCharacter')->findByOwner($user);

        $form = $this->createForm(new PlayerCharacterType(), new PlayerCharacter());
        $form->handleRequest($this->get('request'));

        if($form->isValid()){
            $em->persist($form->getData());

            $em->flush();
        }

        return $this->render('DofBuildBundle:Build:index.html.twig', array('characters' => $characters, 'form' => $form->createView(())));
    }
}
