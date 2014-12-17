<?php

namespace Dof\MonsterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dof\MonsterBundle\Entity\Monster;

class MonsterController extends Controller
{
    public function showAction(Monster $monster)
    {
        $xRay = $this->get('security.context')->isGranted('ROLE_SPELL_XRAY');
        if ($xRay) {
            $dm = $this->getDoctrine()->getManager();
            $spellRepo = $dm->getRepository('DofCharactersBundle:Spell');
            $paramOf = $spellRepo->findByMonsterEffectParam($monster);
        } else
            $paramOf = [ ];

        return $this->render('DofMonsterBundle:Monster:show.html.twig', array('monster' => $monster, 'paramOf' => $paramOf));
    }
}
