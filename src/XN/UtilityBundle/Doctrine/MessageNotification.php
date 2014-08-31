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

            $otherParticipants = $ent->getThread()->getOtherParticipants($ent->getSender());
            $senderUsername = $ent->getSender()->getUsername();
            $threadId = $ent->getThread()->getId();

            foreach($otherParticipants as $participant){
    			$notification = new Notification();

                $notification->setOwner($participant);
                $notification->setType(NotificationType::RECEIVE_MESSAGE);
                $notification->setTranslateString('message.receive');
                $notification->setTranslateParams(array('by' => $senderUsername));

                $notification->setPath('fos_message_thread_view');
                $notification->setParams(array('threadId' => $threadId));

                $em->persist($notification);
            }

            $em->flush();
		}
	}
}
