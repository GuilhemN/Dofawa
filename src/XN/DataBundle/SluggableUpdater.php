<?php

namespace XN\DataBundle;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Patchwork\Utf8;

class SluggableUpdater
{
	private $rootClasses;

	public function __construct()
	{
		$this->rootClasses = [ ];
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof SluggableInterface && $ent->getSlug() === null) {
			$slug = self::slugify(strval($ent));
			if (!$slug)
				throw new \Exception('Empty slug source !');
			$em = $args->getEntityManager();
			$repo = $em->getRepository(get_class($ent));
			if (!$this->tryAssignSlug($ent, $slug, $repo, $em) &&
				!$this->tryAssignSlug($ent, $slug . '-bis', $repo, $em) &&
				!$this->tryAssignSlug($ent, $slug . '-ter', $repo, $em))
				for ($i = 4; !$this->tryAssignSlug($ent, $slug . '-' . $i, $repo, $em); ++$i) { }
		}
	}

	private function tryAssignSlug(SluggableInterface $ent, $slug, EntityRepository $repo, ObjectManager $dm)
	{
		if ($repo->findOneBy([ 'slug' => $slug ]) !== null)
			return false;
		$entRCN = $this->getEntityRootClass($ent, $dm);
		foreach ($dm->getUnitOfWork()->getScheduledEntityInsertions() as $ent2)
			if ($ent2 instanceof SluggableInterface && $this->getEntityRootClass($ent2, $dm) == $entRCN && $ent2->getSlug() == $slug)
				return false;
		$ent->setSlug($slug);
		return true;
	}
	private function getEntityRootClass(SluggableInterface $ent, ObjectManager $dm)
	{
		$cls = get_class($ent);
		if (!isset($this->rootClasses[$cls])) {
			$meta = $dm->getClassMetadata($cls);
			$this->rootClasses[$cls] = $meta->rootEntityName;
		}
		return $this->rootClasses[$cls];
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
