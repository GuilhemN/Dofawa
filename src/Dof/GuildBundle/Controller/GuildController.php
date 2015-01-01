<?php

namespace Dof\GuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use Dof\GuildBundle\Entity\Guild;
use Dof\GuildBundle\Form\GuildType;

class GuildController extends Controller
{
    private $perPage = 15;

    public function indexAction($page)
    {
		$repository = $this->getDoctrine()->getRepository('DofGuildBundle:Guild');
		$guilds = $repository->findBy([], [], ($page - 1) * $this->perPage, $this->perPage);
		$count = $repository->count();

        $pagination = array(
            'page' => $page,
            'route' => 'dof_cms_archive',
            'pages_count' => ceil($count / $this->perPage),
            'route_params' => array()
        );

        return $this->render('DofGuildBundle:Guild:index.html.twig', array('guilds' => $guilds, 'page' => $page, 'pagination' => $pagination));
    }

    public function showAction(Guild $guild)
    {
    	return $this->render('DofGuildBundle:Guild:show.html.twig', array('guild' => $guild));
    }

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     * @Utils\UsesSession
     */
    public function addAction()
    {
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
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     */
    public function registerAction(Guild $guild)
    {

        $user = $this->getUser();
        if($user->getGuild() != null)
        	$registered = true;
        else
        {
            $registered = false;
        	$user->setGuild($guild->getName());
            $this->getDoctrine()->getManager()->flush();
        }

    	return $this->render('DofGuildBundle:Guild:register.html.twig', array('guild' => $guild, 'registered' => $registered, 'user' => $user));
    }
}
