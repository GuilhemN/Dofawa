<?php

namespace XN\DependencyInjection;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

interface RequireSecurityContextInterface
{
    public function getTokenStorage();
    public function setTokenStorage(TokenStorageInterface $tokenStorage);
    public function getAuthorizationChecker();
    public function setAuthorizationChecker(AuthorizationChecker $authorizationChecker);
}
