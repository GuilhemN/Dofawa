<?php

namespace XN\Metadata;

trait MajorColumnsListenerTrait
{
	private static function hasMajorChanges($entity, $chgset)
	{
		$minor = array('updatedAt', 'updater', 'updatedLocale');
		if ($entity instanceof MinorColumnsInterface)
			$minor = array_merge($minor, $entity->getMinorColumns());
		foreach ($chgset as $key => $value)
			if (!in_array($key, $minor))
				return true;
		return false;
	}

	private static function hasMajorChangesWithColumns($entity, $chgset, array $columns) {
		foreach ($chgset as $key => $value)
			if (in_array($key, $columns))
				return true;
		return false;
	}
}
