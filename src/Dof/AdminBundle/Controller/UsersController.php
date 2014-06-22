<?php
namespace Dof\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

//Json response
use XN\UtilityBundle\AjaxControllerTrait;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\UserBundle\Entity\User;

use Dof\AdminBundle\Form\Type\ProfileAdminFormType as AdminFormType;


class UsersController extends Controller 
{

	use AjaxControllerTrait;

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

		return $this->createJsonResponse($result);
	}

	/**
	 * @ParamConverter("user", options={"mapping": {"id": "id"}})
	 */
	function editAction(User $user, Request $request){
		//Vérifie les roles
		$this->canAccess();
 
	    $form = $this->createForm(new AdminFormType(), $user);

	    $form->handleRequest($request);

	    if ($form->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
	    	// Étape 1 : On « persiste » l'entité
		    $em->persist($user);
		    // Étape 2 : On « flush » tout ce qui a été persisté avant
		    $em->flush();

		    // Retour à la page admin des users
		   	return new RedirectResponse($this->container->get('router')->generate('dof_admin_users_homepage'));
	    }

        return $this->container->get('templating')->renderResponse(
            'DofAdminBundle:Users:edit.html.twig',
            array('form' => $form->createView(), 'user' => $user)
        );

	}

	private function canAccess(){
		if (!$this->get('security.context')->isGranted('ROLE_UPDATE_USERS')) 
			throw new AccessDeniedException(__FILE__);
	}
}
