<?php

namespace XN\UtilityBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use XN\Metadata\MajorColumnsListenerTrait;
use XN\Metadata\OwnableInterface;

class OwnableUpdater
{
	use MajorColumnsListenerTrait;

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
		$token = $this->di->get('security.context')->getToken();
		$user = ($token !== null) ? $token->getUser() : null;
		$ent = $args->getEntity();
		if ($ent instanceof OwnableInterface) {
			$ent->setUpdater($user);
			if ($ent->getCreator() === null)
				$ent->setCreator($ent->getUpdater());
			if ($ent->getOwner() === null)
				$ent->setOwner($ent->getUpdater());
		}
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$token = $this->di->get('security.context')->getToken();
		$user = ($token !== null) ? $token->getUser() : null;
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		$mds = array();
		$updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow, $user) {
			return $ent instanceof OwnableInterface && $ent->getUpdater() !== $user && self::hasMajorChanges($ent, $uow->getEntityChangeSet($ent));
		});
		foreach ($updates as $ent) {
			$ent->setUpdater($user);
			$clazz = get_class($ent);
			if (isset($mds[$clazz]))
				$md = $mds[$clazz];
			else {
				$md = $em->getClassMetadata($clazz);
				$mds[$clazz] = $md;
			}
			$uow->recomputeSingleEntityChangeSet($md, $ent);
		}
	}
}
