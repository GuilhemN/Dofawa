<?php

namespace Dof\CharactersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function testAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $spellRank = $em->getRepository('DofCharactersBundle:SpellRank')->findOneById($id);
        return $this->render('DofCharactersBundle:Default:index.html.twig', array('spell' => $spellRank));
    }
}
