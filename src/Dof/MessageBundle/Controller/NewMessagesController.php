<?php

namespace Dof\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NewMessagesController extends Controller
{
	public function menuAction($route)
	{
		$session = $this->get('session');

		$timestamp = $session->get('message/timestamp');
		$hasNew = $session->get('message/hasNew');
		$countNews = $session->get('message/countNews');

		$user = $this->get('security.context')->getToken()->getUser();
		$now = time();

		// MàJ
		if ((($now - $timestamp) > 60 && !$hasNew) || preg_match('~^fos_message~', $route)){
			$repository = $this->getDoctrine()->getRepository('DofMessageBundle:MessageMetadata');
			$countNews = count($repository->findBy(array('participant' => $user, 'isRead' => false)));

			if ($countNews <=0)
				$countNews = 0;

			// Sauvegarde en session
			$session->set('message/hasNew', true);
			$session->set('message/timestamp', time());
			$session->set('message/countNews', $countNews);
		}

		return $this->render('DofMessageBundle:NewMessages:menu.html.twig', array('count' => $countNews));
	}
}
