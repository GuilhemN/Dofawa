<?php

namespace Dof\CharactersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\CharactersBundle\Entity\Spell;

class SpellController extends Controller
{
    public function showAction(Spell $spell)
    {
        if(!$spell->isPubliclyVisible() && !$this->get('security.context')->isGranted('ROLE_SPELL_XRAY'))
            throw $this->createAccessDeniedException();

        return $this->render('DofCharactersBundle:Spell:show.html.twig', array('spell' => $spell));
    }
}
