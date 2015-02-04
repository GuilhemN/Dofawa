<?php

namespace Dof\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArchiveController extends Controller
{
	public function archiveAction($page)
	{
		$translator = $this->get('translator');

		$repository = $this->getDoctrine()->getRepository('DofCMSBundle:Article');
		$countArticles = $repository->countTotal(4);
		$articlesPerPage = 15;
		$firstResult = ($page - 1) * $articlesPerPage;

		if($firstResult > $countArticles)
			throw $this->createNotFoundException('This page does not exist.');

		$articles = $repository->findArticlesWithLimits(4, $firstResult, $articlesPerPage);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setDescription(preg_replace('/<img(.*?)>/', '', $content), $translator->getLocale());
		}


		$pagination = array(
			'page' => $page,
			'route' => 'dof_cms_archive',
			'pages_count' => ceil($countArticles / $articlesPerPage),
			'route_params' => array()
		);

		return $this->render('DofCMSBundle:Archive:archive.html.twig', array(
			'articles' => $articles,
			'page' => $page,
			'pagination' => $pagination
		));

	}
}
