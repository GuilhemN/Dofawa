<?php

namespace Dof\Bundle\MonsterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Dof\Bundle\MonsterBundle\Entity\Monster;

class MonsterController extends Controller
{
    const PER_PAGE = 20;

    public function indexAction ($page, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMonsterBundle:Monster');
        $options = $request->query->get('monster');
        $locale = $this->get('translator')->getLocale();

        $monsters = $repo->findWithOptions($options, ['level' => 'DESC'], self::PER_PAGE, ($page - 1) * self::PER_PAGE, $locale);
        $count = $repo->countWithOptions($options, $locale);
        $pagination = array(
            'page' => $page,
            'route' => $request->attributes->get('_route'),
            'pages_count' => ceil($count / $perPage),
            'route_params' => []
        );
        return $this->render('DofMonsterBundle:Monster:index.html.twig', [
            'monsters' => $monsters,
            'pagination' => $pagination
            ]);
    }

    public function showAction(Monster $monster)
    {
        $xRay = $this->get('security.context')->isGranted('ROLE_SPELL_XRAY');
        if ($xRay) {
            $dm = $this->getDoctrine()->getManager();
            $spellRepo = $dm->getRepository('DofCharacterBundle:Spell');
            $paramOf = $spellRepo->findByMonsterEffectParam($monster);
        } else
            $paramOf = [ ];

        return $this->render('DofMonsterBundle:Monster:show.html.twig', array('monster' => $monster, 'paramOf' => $paramOf));
    }
}
