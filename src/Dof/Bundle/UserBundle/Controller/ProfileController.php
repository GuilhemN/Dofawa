<?php

namespace Dof\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\Bundle\UserBundle\Entity\User;
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
        $em = $this->getDoctrine()->getManager();
        $badges = $em->getRepository('DofMainBundle:BadgeLevel')->getBadgeUser($user);

        return $this->container->get('templating')->renderResponse('DofUserBundle:Profile:index.html.twig', array('user' => $user, 'badges' => $badges));
    }

    /**
     * Show the user.
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $badges = $em->getRepository('DofMainBundle:BadgeLevel')->getBadgeUser($user);

        return $this->container->get('templating')->renderResponse('DofUserBundle:Profile:index.html.twig', array('user' => $user, 'badges' => $badges));
    }
}
