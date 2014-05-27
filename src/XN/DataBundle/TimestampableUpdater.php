<?php

namespace XN\DataBundle;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use \DateTime;

class TimestampableUpdater
{
	use MajorColumnsListenerTrait;
	
	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof TimestampableInterface) {
			$ent->setUpdatedAt(new DateTime('now'));
			if ($ent->getCreatedAt() === null)
				$ent->setCreatedAt($ent->getUpdatedAt());
		}
	}
	
	public function onFlush(OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		$mds = array();
		$updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow) {
			return $ent instanceof TimestampableInterface && self::hasMajorChanges($ent, $uow->getEntityChangeSet($ent));
		});
		foreach ($updates as $ent) {
			$ent->setUpdatedAt(new DateTime('now'));
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