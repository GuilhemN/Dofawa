<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	public function indexAction($type = '')
	{
		$u = $this->get('security.context')->getToken()->getUser();
		$translator = $this->get('translator');

		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofArticlesBundle:Article')->findArticlesWithLimits(4);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setDescription(preg_replace('/<img[^>]>/', '', $content), $translator->getLocales());
		}

		return $this->render('DofMainBundle:Home:index.html.twig', array('articles'=>$articles));
	}

	public function searchEngineAction() {
		return $this->render('DofMainBundle:Default:search.html.twig');
	}

	public function inUpdateAction() {
		return new Response(file_exists('/var/lib/dofawa-repo/update'));
	}
}
