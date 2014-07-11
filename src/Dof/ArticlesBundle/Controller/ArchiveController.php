<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArchiveController extends Controller
{
	public function archiveAction($page)
	{
		$u = $this->get('security.context')->getToken()->getUser();
		$translator = $this->get('translator');

		// 15 results per page
		$lastresult = 15 * $page;
		$firstresult = $lastresult - 14;

		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofArticlesBundle:Article')->setFirstResult($firstresult)->setMaxResults($lastresult);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setDescription(preg_replace('/<img(.*?)>/', '', $content), $translator->getLocales());
		}

		return $this->render('DofArticlesBundle:Archive:archive.html.twig', array('articles'=>$articles, 'page'=>$page));

	}
}