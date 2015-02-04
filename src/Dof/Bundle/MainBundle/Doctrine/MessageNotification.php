<?php

namespace Dof\Bundle\MainBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Dof\Bundle\Social\MessageBundle\Entity\Message;
use Dof\Bundle\MainBundle\Entity\Notification;

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

	public function postPersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof Message) {
            $em = $args->getEntityManager();

            $otherParticipants = $ent->getThread()->getOtherParticipants($ent->getSender());

			$em->flush($ent);

            foreach($otherParticipants as $participant){
				$notification = new Notification();
				$notification
					->setType('message.receive')
					->setOwner($participant)
					->setEntity($ent);
				$em->persist($notification);
			}

			$em->flush();
		}
	}
}
