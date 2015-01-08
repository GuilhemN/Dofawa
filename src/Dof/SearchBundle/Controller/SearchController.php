<?php

namespace Dof\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DofSearchBundle:Default:index.html.twig', array('name' => $name));
    }
}
