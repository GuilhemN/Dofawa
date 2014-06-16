<?php 

namespace Dof\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\UserBundle\Entity\User;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;

class ProfileController extends BaseController
{
	/**
	 * @ParamConverter("user", options={"mapping": {"name_user": "slug"}})
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
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->container->get('templating')->renderResponse('DofUserBundle:Profile:index.html.twig', array('user' => $user));
    }
}
