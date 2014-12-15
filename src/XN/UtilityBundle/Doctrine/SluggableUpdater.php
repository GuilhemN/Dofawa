<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;

use XN\Common\Inflector;
use XN\Metadata\SluggableInterface;

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
		if ($ent instanceof SluggableInterface && $ent->getSlug() === null)
			$this->reassignSlug($ent, $em);
	}

	public function reassignSlug(SluggableInterface $ent, ObjectManager $em)
	{
		$slug = Inflector::slugify(strval($ent));
		if (!$slug)
			throw new \Exception('Empty slug source !');
		$curSlug = $ent->getSlug();
		if ($curSlug !== null && preg_match('#^' . $slug . '(?:-(?:bis|ter|\d+))?$#', $curSlug))
			return;
		$entRCN = $this->getEntityRootClass($ent, $em);
		$repo = $em->getRepository($entRCN);
		if (!$this->tryAssignSlug($ent, $slug, $repo, $entRCN, $em) &&
			!$this->tryAssignSlug($ent, $slug . '-bis', $repo, $entRCN, $em) &&
			!$this->tryAssignSlug($ent, $slug . '-ter', $repo, $entRCN, $em))
			for ($i = 4; !$this->tryAssignSlug($ent, $slug . '-' . $i, $repo, $entRCN, $em); ++$i) { }
	}

	private function tryAssignSlug(SluggableInterface $ent, $slug, EntityRepository $repo, $entRCN, ObjectManager $dm)
	{
		if (self::isReservedSlug($slug))
			return false;
		if ($repo->findOneBy([ 'slug' => $slug ]) !== null)
			return false;
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

	public static function getReservedSlugs()
	{
		static $rslugs = null;
		if ($rslugs === null)
			$rslugs = [
				'any',
				'new'
			];
		return $rslugs;
	}
	public static function isReservedSlug($slug)
	{
		static $rslugs = null;
		if ($rslugs === null) {
			$rslugs = array_flip(self::getReservedSlugs());
			ksort($rslugs);
		}
		return isset($rslugs[$slug]);
	}
}
