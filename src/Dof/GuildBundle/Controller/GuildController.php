<?php

namespace Dof\GuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuildController extends Controller
{
    public function indexAction($page)
    {
    	$guildsPerPage = 15;
		$firstResult = ($page - 1) * $guildsPerPage;

		$repository = $this->getDoctrine()->getRepository('DofGuildBundle:Guild');

		$guilds = $repository->findGuildsWithLimits($firstResult, $guildsPerPage);
		$countGuilds = $repository->count();

		$pagination = array(
   			'page' => $page,
   			'route' => 'dof_articles_archive',
  			'pages_count' => ceil($countGuilds / $articlesPerPage),
   			'route_params' => array()
   		);

        return $this->render('DofGuildBundle:Guild:index.html.twig', array('guilds' => $guilds, 'page' => $page, 'pagination' => $pagination));
    }

    public function addAction()
    {
    	return $this->render('DofGuildBundle:Guild:add.html.twig', array)
    }
}
