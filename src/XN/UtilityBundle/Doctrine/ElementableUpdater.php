<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Dof\ItemsBundle\ElementableInterface;

class ElementableUpdater
{

	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof ElementableInterface) {
			$ent->updateElements();
		}
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		$mds = array();
		$updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow) {
			return $ent instanceof ElementableInterface && self::hasCharactsChanges($ent, $uow->getEntityChangeSet($ent));
		});
		foreach ($updates as $ent) {
			$ent->updateElements();
			$parent = $ent->getParentElements();
			if($parent != null){
				$parent->updateElements();
				$em->persist($parent);

				$pClazz = get_class($parent);
				if (isset($mds[$pClazz]))
					$md = $mds[$pClazz];
				else {
					$md = $em->getClassMetadata($pClazz);
					$mds[$pClazz] = $md;
				}
				$uow->recomputeSingleEntityChangeSet($md, $parent);
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
		$metadata = $entity->getElementsMetadata();
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
