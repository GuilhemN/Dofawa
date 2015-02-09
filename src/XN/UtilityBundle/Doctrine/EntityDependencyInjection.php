<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Component\Security\Core\SecurityContextInterface;

use XN\DependencyInjection\RequireSecurityContextInterface;

class EntityDependencyInjection
{
    private $sc;

    public function __construct(SecurityContextInterface $sc) {
        $this->sc = $sc;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof RequireSecurityContextInterface) {
            $ent->setSecurityContext($this->sc);
        }
    }
}
