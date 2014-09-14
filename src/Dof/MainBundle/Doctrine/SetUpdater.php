<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

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
		elseif ($ent instanceof EquipmentTemplate) {
			$em = $args->getEntityManager();

			$id = $em->getRepository('DofItemsBundle:ItemSet')->getMinimalId() - 1;
			$ent->setId($id);
		}
	}
	public function preRemove(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof EquipmentTemplate && $ent->getSet() !== null) {
			$em = $args->getEntityManager();
			$set = $ent->getSet();
			$set->removeItem($ent);

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
	public function preUpdate(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof EquipmentTemplate) {
			$em = $args->getEntityManager();
			foreach($ent->getOriginalSets() as $set)
				$em->persist($set);
		}
	}
}
