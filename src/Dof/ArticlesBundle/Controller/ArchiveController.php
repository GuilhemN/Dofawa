<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArchiveController extends Controller
{
	public function archiveAction($page)
	{
		 if ($page < 1) {
    		throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "'.$page.'").');
    	}
		$u = $this->get('security.context')->getToken()->getUser();
		$translator = $this->get('translator');

		// 15 results per page
		$lastresult = 15 * $page;
		$firstresult = $lastresult - 14;

		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofArticlesBundle:Article')->findArticlesWithLimits(true, $firstresult, $lastresult);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setDescription(preg_replace('/<img(.*?)>/', '', $content), $translator->getLocales());
		}

		return $this->render('DofArticlesBundle:Archive:archive.html.twig', array('articles'=>$articles, 'page'=>$page));

	}
}