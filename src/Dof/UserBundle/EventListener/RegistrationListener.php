<?php

namespace Dof\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class RegistrationListener implements EventSubscriberInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $di;

	public function __construct(ContainerInterface $di)
	{
		$this->di = $di;
	}
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $this->di->get('notification_manager')->addNotification(null, 'welcome', $this->getUser());
    }
}
