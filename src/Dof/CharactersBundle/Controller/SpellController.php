<?php

namespace Dof\CharactersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\CharactersBundle\Entity\Spell;

class SpellController extends Controller
{
    public function showAction(Spell $spell)
    {
		$xRay = $this->get('security.context')->isGranted('ROLE_SPELL_XRAY');
        if(!$spell->isPubliclyVisible() && !$xRay)
            throw $this->createAccessDeniedException();

		if ($xRay) {
			$dm = $this->getDoctrine()->getManager();
			$spellRepo = $dm->getRepository('DofCharactersBundle:Spell');
			$paramOf = $spellRepo->findBySpellEffectParam($spell);
		} else
			$paramOf = [ ];

        return $this->render('DofCharactersBundle:Spell:show.html.twig', array('spell' => $spell, 'paramOf' => $paramOf));
    }
}
