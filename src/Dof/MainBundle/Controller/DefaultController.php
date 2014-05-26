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

		foreach ($articles as $k => &$article) {
			$content = $article->getMessage();
	    	$article->setMessage(preg_replace('/<img(.*?)>/', '', $content));
		}

		return $this->render('DofMainBundle:Home:index.html.twig', array('articles'=>$articles));
	}
}
