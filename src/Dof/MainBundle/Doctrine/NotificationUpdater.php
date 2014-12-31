<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Dof\MainBundle\Entity\Notification;
use XN\Persistence\IdentifiableInterface;

class NotificationUpdater
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        if ($ent instanceof Notification) {
            if($ent->getEntity() instanceof IdentifiableInterface)
                $ent
                ->setClass($em->getClassMetadata(get_class($ent->getEntity()))->getName())
                ->setClassId($ent->getEntity()->getId())
                ;
        }
    }
}
