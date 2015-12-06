<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use XN\DependencyInjection\LazyServiceBox;
use XN\DependencyInjection\RequireSecurityContextInterface;

class EntityDependencyInjection
{
    private $tokenStorage;
    private $authorizationChecker;

    public function __construct(LazyServiceBox $tokenStorage, LazyServiceBox $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof RequireSecurityContextInterface) {
            $ent->setTokenStorage($this->tokenStorage->unwrap());
            $ent->setAuthorizationChecker($this->authorizationChecker->unwrap());
        }
    }
}
