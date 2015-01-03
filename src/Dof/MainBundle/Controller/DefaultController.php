<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use XN\Annotations as Utils;
use XN\Common\DateFormat;

class DefaultController extends Controller
{
	public function indexAction($type = '')
	{
		$translator = $this->get('translator');

		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofCMSBundle:Article')->findArticlesWithLimits('article');

		return $this->render('DofMainBundle:Home:index.html.twig', array('articles' => $articles));
	}

	public function searchEngineAction() {
		return $this->render('DofMainBundle:Default:search.html.twig');
	}

	/*
	 * @Utils\Secure('ROLE_SUPER_ADMIN')
	 */
	public function inUpdateAction() {
		return new Response((int) file_exists('/var/lib/dofawa-repo/in-update'));
	}

	/*
	* @Utils\Secure('ROLE_SUPER_ADMIN')
	*/
	public function lastUpdateAction() {
		return new Response(DateFormat::formatDate($this->get('translator'), new \Datetime(@file_get_contents('/var/lib/dofawa-repo/update'))));
	}
}
