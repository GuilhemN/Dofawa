<?php

namespace Dof\Bundle\User\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use Symfony\Component\HttpFoundation\Request;
use Dof\Bundle\UserBundle\Entity\User;
use Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter;

class CharacterController extends Controller
{
    /**
     * @Utils\Secure("ROLE_USER")
     */
    public function indexAction(User $user = null){
        if($user === null)
            $user = $this->getUser();
        elseif(!$this->get('security.context')->isGranted('ROLE_ADMIN') && $user !== $this->getUser())
            throw $this->createAccessDeniedException();
        return $this->render('DofUserCharacterBundle:Character:index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Utils\Secure("ROLE_USER")
     */
    public function removeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('DofUserCharacterBundle:PlayerCharacter')->find($request->request->get('id'));
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN') && $character->getOwner() !== $this->getUser())
            throw $this->createAccessDeniedException();
        $em->remove($character);
        $em->flush();
        return $this->redirect($this->generateUrl('dof_user_character_homepage'));
    }
}
