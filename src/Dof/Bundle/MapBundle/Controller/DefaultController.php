<?php

namespace Dof\Bundle\MapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DofMapBundle:Default:index.html.twig', array('name' => $name));
    }
}
