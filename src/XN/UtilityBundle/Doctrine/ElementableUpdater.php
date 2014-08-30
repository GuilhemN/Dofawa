<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Dof\ItemsBundle\ElementableInterface;

class ElementableUpdater
{

	public function prePersist(LifecycleEventArgs $args)
	{
	}

	public function onFlush(OnFlushEventArgs $args)
	{
	}

    protected static function hasCharactsChanges($entity, $chgset){
		$metadata = $entity->getElementsMetadata();
        $charactsFields = array();
        foreach(array_keys($metadata) as $charact)
            $charactsFields += [$charact, 'max' . ucfirst($charact), 'min' . ucfirst($charact)];

		foreach ($chgset as $key => $value)
			if (in_array($key, $charactsFields))
				return true;
		return false;
	}
}
