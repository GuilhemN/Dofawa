<?php

namespace Dof\Bundle\QuestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuestManagerController extends Controller
{
    public function indexAction()
    {
        return $this->render('DofQuestBundle:Default:index.html.twig', array('name' => $name));
    }
}
