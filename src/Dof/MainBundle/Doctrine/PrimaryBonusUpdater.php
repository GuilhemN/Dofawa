<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Dof\ItemsBundle\PrimaryBonusInterface;

class PrimaryBonusUpdater
{

	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		$em = $args->getEntityManager();
		if ($ent instanceof PrimaryBonusInterface) {
			$ent->updatePrimaryBonus();
			$cascadeClass = $ent->getCascadeForPrimaryBonus();
		}
	}

	public function preRemove(LifecycleEventArgs $args)
	{
		$this->prePersist($args);
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		$mds = array();
		$updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow) {
			return $ent instanceof PrimaryBonusInterface && self::hasCharactsChanges($ent, $uow->getEntityChangeSet($ent));
		});
		foreach ($updates as $ent) {
			$ent->updatePrimaryBonus();
			$cascadeClass = $ent->getCascadeForPrimaryBonus();
			if($cascadeClass != null){
				$cascadeClass->updatePrimaryBonus();
				$em->persist($cascadeClass);

				$pClazz = get_class($cascadeClass);
				if (isset($mds[$pClazz]))
					$md = $mds[$pClazz];
				else {
					$md = $em->getClassMetadata($pClazz);
					$mds[$pClazz] = $md;
				}
				$uow->recomputeSingleEntityChangeSet($md, $cascadeClass);
			}

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

    protected static function hasCharactsChanges($entity, $chgset){
		$metadata = $entity->getPrimaryBonusFields();
        $charactsFields = array();
        foreach(array_keys($metadata) as $charact){
            $charactsFields[ ] = $charact;
			$charactsFields[ ] = 'max' . ucfirst($charact);
			$charactsFields[ ] = 'min' . ucfirst($charact);
		}

		foreach ($chgset as $key => $value)
			if (in_array($key, $charactsFields))
				return true;
		return false;
	}
}
