<?php

namespace Dof\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\UserBundle\Entity\User;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
	/**
	 * @ParamConverter("user", options={"mapping": {"slug": "slug"}})
	 */
    public function userpageAction(User $user)
    {
        return $this->container->get('templating')->renderResponse('DofUserBundle:Profile:index.html.twig', array('user' => $user));
    }

    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException();
        }

        return $this->container->get('templating')->renderResponse('DofUserBundle:Profile:index.html.twig', array('user' => $user));
    }
}
