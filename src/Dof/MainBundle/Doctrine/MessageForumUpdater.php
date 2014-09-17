<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

use Dof\ForumBundle\Entity\Message;

class MessageForumUpdater
{
	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof Message) {

			$ent->getTopic()->setLastPost(new \DateTime());
        	$ent->getTopic()->setCountPosts($ent->getTopic()->getCountPosts()+1) ;
		}
	}
	public function preRemove(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof Message) {
			$ent->getTopic()->removeMessage($ent);

			$lastDate = $ent->getTopic()->getLastMessageCreatedAt();

			$ent->getTopic()->setLastPost($lastDate);
        	$ent->getTopic()->setCountPosts($ent->getTopic()->getCountPosts()-1) ;
		}
	}
}