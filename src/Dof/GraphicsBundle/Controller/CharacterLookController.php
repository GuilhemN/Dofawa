<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Dof\GraphicsBundle\Entity\CharacterLook;

class CharacterLookController extends Controller
{
    public function createAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_STYLIST_BETA'))
            throw new AccessDeniedException();

        $cl = new CharacterLook();
        $form = $this->createForm('character_look', $cl);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if(!$this->get('security.context')->isGranted('ROLE_STYLIST'))
                $cl->setPubliclyVisible(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cl);
            $em->flush();
        }

        return $this->render('DofGraphicsBundle:CharacterLook:create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
