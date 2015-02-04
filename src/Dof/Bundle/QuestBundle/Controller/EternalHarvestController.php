<?php

namespace Dof\Bundle\QuestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EternalHarvestController extends Controller
{
    public function indexAction()
    {
        $quest = $this->getQuest();
        return $this->render('DofQuestBundle:EternalHarvest:index.html.twig', array('quest' => $quest));
    }

    private function getQuest() {
        return $this->getDoctrine()->getManager()->getRepository('DofQuestBundle:Quest')->findOneBySlug('l-eternel-moisson');
    }
}
