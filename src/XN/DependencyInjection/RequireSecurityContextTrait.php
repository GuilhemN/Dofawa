<?php
namespace XN\DependencyInjection;

use Symfony\Component\Security\Core\SecurityContextInterface;

trait RequireSecurityContextTrait
{
    private $sc;

    public function getSecurityContext(){
        return $this->sc;
    }

    public function setSecurityContext(SecurityContextInterface $sc) {
        $this->sc = $sc;
    }
}
