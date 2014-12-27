<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CalculateurController extends Controller
{
	public function indexAction()
	{
		return $this->render('DofMainBundle:Calculateur:main.html.twig');
	}
}
