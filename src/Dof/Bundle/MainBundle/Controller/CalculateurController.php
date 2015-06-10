<?php

namespace Dof\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalculateurController extends Controller
{
    public function indexAction()
    {
        return $this->render('DofMainBundle:Calculateur:main.html.twig');
    }
}
