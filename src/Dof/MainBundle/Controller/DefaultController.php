<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction($type = '')
	{
		$u = $this->get('security.context')->getToken()->getUser();
		$translator = $this->get('translator');

		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofArticlesBundle:Articles')->findArticlesWithLimits(true);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setMessage(preg_replace('/<img(.*?)>/', '', $content));
		}

		return $this->render('DofMainBundle:Home:index.html.twig', array('articles'=>$articles));

	}
}
