<?php 

namespace Dof\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\UserBundle\Entity\User;

class ProfileController extends Controller
{
	/**
	 * @ParamConverter("user", options={"username" = "name_user"})
	 */
    public function indexAction(User $user)
    {
        return $this->render('DofUserBundle:Profile:index.html.twig', array('user' => $user));
    }
}
