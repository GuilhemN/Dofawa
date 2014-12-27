<?php
namespace XN\UtilityBundle\Annotation;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Annotations\Reader;
use XN\UtilityBundle\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SessionControllerListener
{
    private $sc;
    private $se;
    private $re;
    private $pb;
    private $di;

    public function __construct(SecurityContext $sc, Session $se, Reader $re, ParameterBag $pb, ContainerInterface $di){
        $this->sc = $sc;
        $this->se = $se;
        $this->re = $re;
        $this->pb = $pb;
        $this->di = $di;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if($event->isMasterRequest())
            return;

        $session = $this->di->get('session');
        $this->pb->setParameter('dof_user_last_username', (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME));
        $this->pb->setParameter('dof_user_csrf_authenticate', $this->di->get('form.csrf_provider')->generateCsrfToken('authenticate'));

        $controller = $event->getController();
        if (!is_array($controller))
            // not a object but a different kind of callable. Do nothing
            return;
            
        $token = $this->sc->getToken();
        if($token)
            $token->getUser();

        if(!$this->re->getMethodAnnotation(new \ReflectionMethod($controller[0], $controller[1]), 'XN\Annotations\UsesSession'))
            $this->se->save();
    }
}
