<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use XN\Annotations as Utils;

class DefaultController extends Controller
{
	public function indexAction($type = '')
	{
		$translator = $this->get('translator');

		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofCMSBundle:Article')->findArticlesWithLimits(4);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setDescription(preg_replace('/<img[^>]>/', '', $content), $translator->getLocales());
		}

		return $this->render('DofMainBundle:Home:index.html.twig', array('articles' => $articles));
	}

	public function searchEngineAction() {
		return $this->render('DofMainBundle:Default:search.html.twig');
	}

	/*
	 * @Utils\Secure('ROLE_SUPER_ADMIN')
	 */
	public function inUpdateAction() {
		return new Response((string) file_exists('/var/lib/dofawa-repo/update'));
	}
}
