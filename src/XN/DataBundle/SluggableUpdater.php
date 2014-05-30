<?php

namespace XN\DataBundle;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Patchwork\Utf8;

class SluggableUpdater
{
	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof SluggableInterface && $ent->getSlug() === null) {
			$slug = self::slugify(strval($ent));
			if (!$slug)
				throw new \Exception('Empty slug source !');
			$em = $args->getEntityManager();
			$repo = $em->getRepository(get_class($ent));
			if (!$this->tryAssignSlug($ent, $slug, $repo) &&
				!$this->tryAssignSlug($ent, $slug . '-bis', $repo) &&
				!$this->tryAssignSlug($ent, $slug . '-ter', $repo))
				for ($i = 4; !$this->tryAssignSlug($ent, $slug . '-' . $i, $repo); ++$i) { }
		}
	}
	
	private function tryAssignSlug(SluggableInterface $ent, $slug, EntityRepository $repo)
	{
		if ($repo->findOneBy([ 'slug' => $slug ]) !== null)
			return false;
		$ent->setSlug($slug);
		return true;
	}
	
	public static function slugify($str)
	{
		$str = Utf8::toAscii($str);
		$str = strtolower($str);
		$str = preg_replace('/[^a-z0-9-]+/', '-', $str);
		$str = trim($str, '-');
		return $str;
	}
}
