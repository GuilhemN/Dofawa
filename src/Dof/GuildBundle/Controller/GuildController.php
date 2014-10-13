<?php

namespace Dof\GuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\GuildBundle\Entity\Guild;
use Dof\GuildBundle\Form\GuildType;

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
  			'pages_count' => ceil($countGuilds / $guildsPerPage),
   			'route_params' => array()
   		);

        return $this->render('DofGuildBundle:Guild:index.html.twig', array('guilds' => $guilds, 'page' => $page, 'pagination' => $pagination));
    }

    /**
   	* @ParamConverter("guild")
   	*/
    public function showAction(Guild $guild)
    {
    	return $this->render('DofGuildBundle:Guild:show.html.twig', array('guild' => $guild));
    }

    public function addAction()
    {
    	if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

    $guild = new Guild;
		$form = $this->createForm(new GuildType, $guild);

		$request = $this->get('request');
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

		    if ($form->isValid()) {

		    	$em = $this->getDoctrine()->getManager();
		      	$em->persist($guild);
		      	$em->flush();

		      	return $this->redirect($this->generateUrl('dof_guild_home'));
		    }
		} 
    	return $this->render('DofGuildBundle:Guild:add.html.twig', array('form' => $form->createView()));
    }

    /**
   	* @ParamConverter("guild")
   	*/
    public function registerAction(Guild $guild)
    {
    	if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();
        $registred = false;

        $user = $this->getUser();
        if($user->getGuilde() != "")
        	$registred = true;
        else
        {
        	$user->setGuilde($guild->getName());

        	$em = $this->getDoctrine()->getManager();
		    $em->persist($user);
		    $em->flush();
        }

    	return $this->render('DofGuildBundle:Guild:register.html.twig', array('guild' => $guild, 'registred' => $registred, 'user' => $user));
    }
}
