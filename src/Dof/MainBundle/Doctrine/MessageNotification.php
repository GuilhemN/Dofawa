<?php

namespace Dof\MainBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Dof\MessageBundle\Entity\Message;
use Dof\MainBundle\Entity\Notification;
use Dof\MainBundle\NotificationType;

class MessageNotification
{
	/**
	 * @var ContainerInterface
	 */
	private $di;

	public function __construct(ContainerInterface $di)
	{
		$this->di = $di;
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof Message) {
            $em = $args->getEntityManager();

            $otherParticipants = $ent->getThread()->getOtherParticipants($ent->getSender());

			$em->flush($ent);

			$nm = $this->di->get('notification_manager');
            foreach($otherParticipants as $participant)
                $nm->addNotification($ent, 'message.receive', $participant);
		}
	}
}
