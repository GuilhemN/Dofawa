<?php 

namespace Dof\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProfileController extends Controller
{
	/**
	 * @ParamConverter("user", options={"mapping": {"username": "name_user"}})
	 */
    public function indexAction(User $user)
    {
        return $this->render('DofUserBundle:Profile:index.html.twig', array('user' => $user));
    }
}
