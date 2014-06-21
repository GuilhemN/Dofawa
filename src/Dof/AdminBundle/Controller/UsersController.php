<?php
namespace Dof\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class UsersController extends Controller
{
    public function indexAction($page)
    {
    	if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) throw new AccessDeniedException(__FILE__);

        return $this->render('DofAdminBundle:Users:index.html.twig');
    }
}
