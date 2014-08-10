<?php

namespace Dof\BuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\BuildBundle\Entity\PlayerCharacter;

class BuildController extends Controller
{
    public function indexAction()
    {
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            $this->createAccessDeniedException();

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $characters = $em->getRepository('DofBuildBundle:PlayerCharacter')->findByOwner($user);

        $form = $this->createForm('dof_buildbundle_playercharacter', new PlayerCharacter());
        $form->handleRequest($this->get('request'));

        if($form->isValid()){
            $em->persist($form->getData());

            $em->flush();
        }

        return $this->render('DofBuildBundle:Build:index.html.twig', array('characters' => $characters, 'form' => $form->createView()));
    }
}
