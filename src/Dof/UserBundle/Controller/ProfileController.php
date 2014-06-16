<?php 

namespace Dof\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\UserBundle\Entity\User;

use FOS\UserBundle\Controller\ProfileController as BaseController;

class ProfileController extends BaseController
{
	/**
	 * @ParamConverter("user", options={"mapping": {"name_user": "slug"}})
	 */
    public function userpageAction(User $user)
    {
        return $this->container->get('templating')->renderResponse('DofUserBundle:Profile:index.html.twig', array('user' => $user));
    }
}
