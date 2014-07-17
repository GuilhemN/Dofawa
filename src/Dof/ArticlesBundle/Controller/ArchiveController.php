<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArchiveController extends Controller
{
	public function archiveAction($page)
	{
		$translator = $this->get('translator');

		$repository = $this->getDoctrine()->getRepository('DofArticlesBundle:Article');
		$coutArticles = $repository->countTotal(true);
		$articlesPerPage = 15;
		$firstResult = ($page - 1) * $articlePerPage;

		if($firstResult > $countArticles)
            throw $this->createNotFoundException('This page does not exist.');

		$articles = $repository->findArticlesWithLimits(true, $firstResult, $articlesPerPage);

		foreach ($articles as $k => &$article) {
			$content = $article->getDescription($translator->getLocales());
			$article->setDescription(preg_replace('/<img(.*?)>/', '', $content), $translator->getLocale());
		}


		$pagination = array(
   			'page' => $page,
   			'route' => 'dof_articles_archive',
  			'pages_count' => ceil($coutArticles / $articlesPerPage),
   			'route_params' => array()
   		);

		return $this->render('DofArticlesBundle:Archive:archive.html.twig', array(
			'articles' => $articles,
			'page' => $page,
			'pagination' => $pagination
		));

	}
}
