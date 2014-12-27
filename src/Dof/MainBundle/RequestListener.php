<?php
namespace Dof\MainBundle;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;

class RequestListener
{
    private $sc;

    public function __construct(SecurityContext $sc){
        $this->sc = $sc;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $token = $this->sc->getToken();
        if($token)
            $token->getUser();
    }
}
