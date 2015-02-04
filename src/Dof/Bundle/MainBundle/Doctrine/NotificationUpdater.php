<?php

namespace Dof\Bundle\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Dof\Bundle\MainBundle\Entity\Notification;
use XN\Persistence\IdentifiableInterface;

class NotificationUpdater
{
    # TODO : REMOVE IT
    public function prePersist(LifecycleEventArgs $args)
    {
    }
}
