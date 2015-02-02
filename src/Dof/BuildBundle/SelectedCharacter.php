<?php
namespace Dof\BuildBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\SecurityContext;

use Dof\UserBundle\Entity\User;

class SelectedCharacter
{
    private $em;

    public function __construct(ObjectManager $em, SecurityContext $sc) {
        $this->em = $em;
        $this->sc = $sc;
    }

    public function find(User $user = null) {
        if($user === null)
            $user = $this->getUser();
        if($user === null)
            return;
        $repo = $this->em->getRepository('XNUtilityBundle:LoggedAction');
        return $repo->findLastCharacter($user);
    }

    public function getUser() {
        return $this->sc->isGranted('ROLE_USER') ? $this->sc->getToken()->getUser() : null;
    }
}
