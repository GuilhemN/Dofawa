<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Dof\ItemsBundle\Entity\EquipmentTemplate;
use Dof\ItemsBundle\Entity\ItemSet;

class SetUpdater
{
	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof ItemSet) {
			$em = $args->getEntityManager();

			$id = $em->getRepository('DofItemsBundle:ItemSet')->getMinimalId() - 1;
			$ent->setId($id);
		}
	}
	public function preRemove(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof EquipmentTemplate) {
			$em = $args->getEntityManager();
			$set = $ent->getSet();

			$maxLevel = 0;
			foreach($set->getItems() as $item){
				if($maxLevel < $item->getLevel())
					$maxLevel = $item->getLevel();
				if($item->getLevel() == 200)
					break;
			}

			$set->setLevel($maxLevel);
			$set->setItemCount(count($set->getItems()));

			$em->persist($set);
		}
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		$mds = array();
		$updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow) {
			return $ent instanceof EquipmentTemplate && self::hasCharactsChanges($ent, $uow->getEntityChangeSet($ent));
		});
		foreach ($updates as $ent) {
			foreach($ent->getOriginalSets() as $set){
				$maxLevel = 0;
				foreach($set->getItems() as $item){
					if($maxLevel < $item->getLevel())
						$maxLevel = $item->getLevel();
					if($item->getLevel() == 200)
						break;
				}

				$set->setLevel($maxLevel);
				$set->addItem($ent);
				$set->setItemCount(count($set->getItems()));

				$clazz = get_class($set);
				if (isset($mds[$clazz]))
					$md = $mds[$clazz];
				else {
					$md = $em->getClassMetadata($clazz);
					$mds[$clazz] = $md;
				}
				$uow->recomputeSingleEntityChangeSet($md, $set);
			}
		}
	}

    protected static function hasCharactsChanges($entity, $chgset){
		$fields = ['set', 'level'];

		foreach ($chgset as $key => $value)
			if (in_array($key, $fields))
				return true;
		return false;
	}
}
