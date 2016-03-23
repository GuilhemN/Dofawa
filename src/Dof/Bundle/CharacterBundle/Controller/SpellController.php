<?php

namespace Dof\Bundle\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Templating\EngineInterface;
use Dof\Bundle\CharacterBundle\Entity\Spell;
use Doctrine\Common\Persistence\ManagerRegistry;

class SpellController extends Controller
{
    private $authorizationChecker;
    private $doctrine;
    private $engine;

    public function __construct (AuthorizationCheckerInterface $authorizationChecker, ManagerRegistry $doctrine, EngineInterface $engine) {
        $this->authorizationChecker = $authorizationChecker;
        $this->doctrine = $doctrine;
        $this->engine = $engine;
    }

    public function showAction(Spell $spell)
    {
        $xRay = $this->authorizationChecker->isGranted('ROLE_SPELL_XRAY');
        if (!$spell->isPubliclyVisible() && !$xRay) {
            throw new AccessDeniedException();
        }

        if ($xRay) {
            $repository = $this->doctrine->getRepository(Spell::class);
            $paramOf = $repository->findBySpellEffectParam($spell);
        } else {
            $paramOf = [];
        }

        return $this->templating->renderResponse('DofCharacterBundle:Spell:show.html.twig', array('spell' => $spell, 'paramOf' => $paramOf));
    }
}
