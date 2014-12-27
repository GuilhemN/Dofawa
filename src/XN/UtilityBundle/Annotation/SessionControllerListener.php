<?php
namespace XN\UtilityBundle\Annotation;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Annotations\Reader;

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

        if(!$this->re->getMethodAnnotation(new \ReflectionMethod($controller[0], $controller[1]), 'XN\Annotation\UsesSession'))
            $this->se->save();
    }
}
