<?php

namespace Dof\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\UserBundle\Model\UserInterface;

class AccountController extends Controller
{
    public function securityAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface)
            throw $this->createAccessDeniedException();

        return $this->render('DofUserBundle:Account:security.html.twig', ['user' => $user]);
    }
}
