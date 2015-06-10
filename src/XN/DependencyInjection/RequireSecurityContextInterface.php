<?php

namespace XN\DependencyInjection;

use Symfony\Component\Security\Core\SecurityContextInterface;

interface RequireSecurityContextInterface
{
    public function getSecurityContext();
    public function setSecurityContext(SecurityContextInterface $sc);
}
