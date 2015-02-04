<?php

namespace Dof\Bundle\MainBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

use Dof\Bundle\Social\ForumBundle\Entity\Message;
use Dof\Bundle\UserBundle\Entity\User;

class MessageForumUpdater
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

			$ent->getTopic()->setLastPost(new \DateTime());
			$ent->getTopic()->cleanReadBy();
			if($this->di->get('security.context')->getToken()->getUser() !== null)
			{
				$user = $this->di->get('security.context')->getToken()->getUser();
				$ent->getTopic()->addReadBy($user);
			}
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