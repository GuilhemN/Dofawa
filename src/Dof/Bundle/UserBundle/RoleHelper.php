<?php

namespace App\UserBundle;

use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RoleHelper
{
    private $roleHierarchy;
    private $tokenStorage;

    public function __construct(RoleHierarchyInterface $roleHierarchy, TokenStorage $tokenStorage)
    {
        $this->roleHierarchy = $roleHierarchy;
        $this->tokenStorage = $tokenStorage;
    }

    public function getCurrentRoles()
    {
        return array_values(array_unique(array_map(
            function ($v) {
                return $v->getRole();
            },
            $this->roleHierarchy->getReachableRoles($this->tokenStorage->getToken()->getRoles())
        )));
    }
}
