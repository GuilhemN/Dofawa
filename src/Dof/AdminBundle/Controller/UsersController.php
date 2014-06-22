<?php
namespace Dof\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

//Json response
use XN\UtilityBundle\AjaxControllerTrait;

class UsersController extends Controller implements AjaxControllerTrait
{
	public function indexAction($page)
	{
		//Vérifie les roles
		$this->canAccess();

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

		$users = $this->getDoctrine()->getRepository('DofUserBundle:User')
		->getList($page, $maxUsers);

		return $this->render('DofAdminBundle:Users:index.html.twig', array(
			'users' => $users,
			'pagination' => $pagination
			));
	}

	function deleteAction($id){
		//Vérifie les roles
		$this->canAccess();

		$result = $this->getDoctrine()
		->getRepository('DofUserBundle:User')
		->deleteById($id);

		$this->createJsonResponse($result);
	}

	private function canAccess(){
		if (!$this->get('security.context')->isGranted('ROLE_UPDATE_USERS')) 
			throw new AccessDeniedException(__FILE__);
	}
}
