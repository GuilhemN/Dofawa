<?php

namespace Dof\QuestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DofQuestBundle:Default:index.html.twig', array('name' => $name));
    }
}
