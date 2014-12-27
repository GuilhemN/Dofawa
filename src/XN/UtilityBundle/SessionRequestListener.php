<?php
namespace Dof\MainBundle;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Annotations\Reader;

use Doctrine\Common\Annotations\AnnotationReader;

class SessionControllerListener
{
    private $sc;
    private $se;
    private $re;

    public function __construct(SecurityContext $sc, Session $se, Reader $re){
        $this->sc = $sc;
        $this->se = $se;
        $this->re = $re;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller))
            // not a object but a different kind of callable. Do nothing
            return;

        $token = $this->sc->getToken();
        if($token)
            $token->getUser();

        $reader = new AnnotationReader();
        if(!$this->re->getMethodAnnotation(new \ReflectionMethod($controller[0], $controller[1]), 'XN\Common\UsesSession'))
            $this->se->save();
    }
}
