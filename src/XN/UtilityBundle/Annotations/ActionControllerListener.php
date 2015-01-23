<?php
namespace XN\UtilityBundle\Annotations;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use XN\Annotations\Action;

use XN\UtilityBundle\ActionEvents;
use XN\UtilityBundle\Event\CreateActionEvent;

class ActionControllerListener
{
    private $re;
    private $dispatcher;

    public function __construct(Reader $re, EventDispatcherInterface $dispatcher){
        $this->re = $re;
        $this->dispatcher = $dispatcher;
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
            return $v instanceof Action;
        });

        foreach($annotations as $annotation)
            $this->dispatcher->dispatch(ActionEvents::CREATE, new CreateActionEvent($annotation->name, $annotation->context));
    }
}
