<?php
namespace XN\UtilityBundle\Annotation;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SessionControllerListener
{
    private $sc;
    private $se;
    private $re;
    private $di;

    public function __construct(SecurityContext $sc, Session $se, Reader $re, ContainerInterface $di){
        $this->sc = $sc;
        $this->se = $se;
        $this->re = $re;
        $this->di = $di;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller))
            // not a object but a different kind of callable. Do nothing
            return;

        $session = $this->di->get('session');
        $this->di->setParameter('dof_user.last_username', (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME));
        $this->di->setParameter('dof_user.csrf_authenticate', $this->di->get('form.csrf_provider')->generateCsrfToken('authenticate'));

        $token = $this->sc->getToken();
        if($token)
            $token->getUser();

        if(!$this->re->getMethodAnnotation(new \ReflectionMethod($controller[0], $controller[1]), 'XN\Annotations\UsesSession'))
            $this->se->save();
    }
}
