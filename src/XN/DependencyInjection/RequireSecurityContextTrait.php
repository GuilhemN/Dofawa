<?php

namespace XN\DependencyInjection;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

trait RequireSecurityContextTrait
{
    private $tokenStorage;
    private $authorizationChecker;

    public function getTokenStorage()
    {
        return $this->tokenStorage;
    }

    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getAuthorizationChecker()
    {
        return $this->authorizationChecker;
    }

    public function setAuthorizationChecker(AuthorizationChecker $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }
}
