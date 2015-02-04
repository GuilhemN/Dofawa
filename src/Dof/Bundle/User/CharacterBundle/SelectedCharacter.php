<?php
namespace Dof\Bundle\User\CharacterBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\SecurityContext;

use Dof\Bundle\UserBundle\Entity\User;

class SelectedCharacter
{
    private $em;
    private $sc;

    private $stuff;
    private $processed;

    public function __construct(ObjectManager $em, SecurityContext $sc) {
        $this->em = $em;
        $this->sc = $sc;
    }

    public function find() {
        if($this->processed)
            return $this->character;
        $user = $this->getUser();
        if($user === null)
            return;
        $repo = $this->em->getRepository('XNUtilityBundle:LoggedAction');
        $this->processed = true;
        $this->stuff = $repo->findLastStuff($user);
        return $this->stuff->getCharacter();
    }

    public function findLastStuff() {
        $this->find();
        return $this->stuff;
    }

    public function getUser() {
        return $this->sc->isGranted('ROLE_USER') ? $this->sc->getToken()->getUser() : null;
    }
}
