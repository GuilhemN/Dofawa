<?php

namespace Dof\Bundle\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function testAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $spellRank = $em->getRepository('DofCharacterBundle:SpellRank')->findOneById($id);
        return $this->render('DofCharacterBundle:Default:index.html.twig', array('spell' => $spellRank));
    }
}
