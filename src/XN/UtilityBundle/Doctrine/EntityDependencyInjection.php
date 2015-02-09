<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;

use XN\DependencyInjection\LazyServiceBox;

use XN\DependencyInjection\RequireSecurityContextInterface;

class EntityDependencyInjection
{
    private $sc;

    public function __construct(LazyServiceBox $sc) {
        $this->sc = $sc;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof RequireSecurityContextInterface) {
            $ent->setSecurityContext($this->sc->unwrap());
        }
    }
}
