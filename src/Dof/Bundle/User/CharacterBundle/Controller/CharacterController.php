<?php

namespace Dof\Bundle\User\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use Dof\Bundle\UserBundle\Entity\User;

class CharacterController extends Controller
{
    /**
     * @Utils\Secure("ROLE_USER")
     */
    public function indexAction(User $user = null){
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN') && $user !== $this->getUser())
            $em = $this->getDoctrine()->getManager();
        return $this->render('DofUserCharacterBundle:Character:index.html.twig', [
            'user' => $user
        ]);
    }
}
