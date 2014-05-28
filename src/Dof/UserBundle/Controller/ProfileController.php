<?php 

namespace Dof\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
	/**
	 * @Route("/{name_user}")
	 * @ParamConverter("user", class="DofUserBundle:User", options={"username" = "name_user"})
	 */
    public function indexAction(User $user)
    {
        return $this->render('DofUserBundle:Profile:index.html.twig', array('user' => $user));
    }
}
