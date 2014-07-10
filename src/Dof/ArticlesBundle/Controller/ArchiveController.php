<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArchiveController extends Controller
{
	public function archiveAction()
	{
		$u = $this->get('security.context')->getToken()->getUser();
		$translator = $this->get('translator');

		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofArticlesBundle:Article')->findArticlesWithLimits(true);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setDescription(preg_replace('/<img(.*?)>/', '', $content), $translator->getLocales());
		}

		return $this->render('DofArticlesBundle:Archive:archive.html.twig', array('articles'=>$articles));

	}
}