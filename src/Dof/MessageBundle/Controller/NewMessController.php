<?php

namespace Dof\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NewMessController extends Controller
{
	public function indexAction()
	{
		$session = $this->get('session');

		$timestamp = $session->get('message/timestamp');
		$hasNew = $session->get('message/hasNew');
		$countNews = $session->get('message/countNews');

		$idUser = $this->get('security.context')->getToken()->getUser()->getId();
		$now = time();
		
		// MàJ
		if (($now - $timestamp) > 60 && !$hasNew){
			$repository = $this->getDoctrine()->getRepository('DofMessageBundle:MessageMetadata');
			$countNews = $repository->findBy(array('participant_id' => $idUser, 'is_read' => false))->count();

			if ($countNews <=0)
				$countNews = 0;

			// Sauvegarde en session
			$session->set('message/hasNew',true);
			$session->set('message/timestamp',time());
			$session->set('message/countNews',$countNews);
		}

		$response = '<span class="glyphicon glyphicon-envelope"></span> Messages <span class="badge">'.$countNews.'</span>';
		return new Response($response);
	}
}