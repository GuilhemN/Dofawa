<?php
namespace Dof\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class UsersController extends Controller
{
	public function indexAction($page)
	{
		if (!$this->get('security.context')->isGranted('ROLE_UPDATE_USERS')) throw new AccessDeniedException(__FILE__);

		$maxUsers = 30;
		$users_count = $this->getDoctrine()
		->getRepository('DofUserBundle:User')
		->countTotal();

		$pagination = array(
			'page' => $page,
			'route' => 'dof_admin_users_homepage',
			'pages_count' => ceil($users_count / $maxUsers),
			'route_params' => array()
			);

		$articles = $this->getDoctrine()->getRepository('DofUserBundle:User')
		->getList($page, $maxUsers);

		return $this->render('DofAdminBundle:Users:index.html.twig', array(
			'articles' => $articles,
			'pagination' => $pagination
			));
	}
}
