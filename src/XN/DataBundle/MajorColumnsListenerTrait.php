<?php

namespace XN\DataBundle;

trait MajorColumnsListenerTrait
{
	private static function hasMajorChanges($entity, $chgset)
	{
		$minor = array('updatedAt', 'updater');
		if ($entity instanceof MinorColumnsInterface)
			$minor = array_merge($minor, $entity->getMinorColumns());
		foreach ($chgset as $key => $value)
			if (!in_array($key, $minor))
				return true;
		return false;
	} 
}