<?php

namespace Dof\Bundle\User\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

class CharacterController extends Controller
{
    /**
     * @Utils\Secure("ROLE_USER")
     */
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        return $this->render('DofUserCharacterBundle:Character:index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
