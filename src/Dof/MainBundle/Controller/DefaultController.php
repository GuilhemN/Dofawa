<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction()
	{
		$u = $this->get('security.context')->getToken()->getUser();


		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofArticlesBundle:Articles')->findArticlesWithLimits(true);

		return $this->render('DofMainBundle:Home:index.html.twig', array('articles'=>$articles));
	}
}
