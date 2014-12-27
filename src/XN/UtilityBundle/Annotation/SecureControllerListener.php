<?php
namespace XN\UtilityBundle\Annotation;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Common\Annotations\Reader;

use XN\Annotation\Secure;

class SecureControllerListener
{
    private $sc;

    public function __construct(SecurityContext $sc){
        $this->sc = $sc;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller))
            // not a object but a different kind of callable. Do nothing
            return;

        $annotations = $this->re->getClassAnnotations(new \ReflectionClass($controller[0]))
            + $this->re->getMethodAnnotations(new \ReflectionMethod($controller[0], $controller[1]));

        $annotations = array_filter($annotations, function ($v) {
            return $v instanceof Secure;
        });

        foreach($annotations as $annotation)
            if(!$this->sc->isGranted($annotation->role))
                throw new AccessDeniedException(sprintf('Current user is not granted required role "%s".', $annotation->role));
    }
}
