<?php
namespace Acme\UploadBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use XN\UtilityBundle\ActionEvents;
use XN\UtilityBundle\Event\CreateActionEvent;

use XN\UtilityBundle\ActionLogger;

class ActionSubscriber implements EventSubscriberInterface
{
    private $al;

    public function __construct(ActionLogger $al) {
        $this->al = $al;
    }

    public static function getSubscribedEvents() {
        // Liste des évènements écoutés et méthodes à appeler
        return array(
            ActionEvents::CREATE => 'create'
        );
    }

    public function create(CreateActionEvent $event) {
        $this->al->set($event->getName(), $event->getContext());
    }
}
