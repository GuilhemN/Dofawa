<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Dof\MessageBundle\Entity\Message;
use Dof\MainBundle\Entity\Notification;
use Dof\MainBundle\NotificationType;

class MessageNotification
{

	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof Message) {
            $em = $args->getEntityManager();

			$notification = new Notification();

            $notification->setOwner($ent->getMetadata()->getParticipant());
            $notification->setType(NotificationType::RECEIVE_MESSAGE);
            $notification->setTranslateString('message.receive');
            $notification->setTranslateParams(array('by' => $ent->getSender()->getUsername()));

            $notification->setPath('fos_message_thread_view');
            $notification->setParams(array('threadId' => $ent->getThread()->getId()));

            $em->persist($notification);
            $em->flush();
		}
	}
}
