<?php

namespace Dof\ItemsBundle\Entity;

use XN\Rest\FilterableEntityRepository;

use Doctrine\ORM\QueryBuilder;

/**
 * ItemTemplateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemTemplateRepository extends FilterableEntityRepository
{
	protected function getFilterableExpr(QueryBuilder $qb, $alias, $locale = 'fr')
	{
		$primary = $alias . ".name" . ucfirst($locale);
		$secondary = $alias . ".nameFr";

		return  " CASE WHEN (" . $primary . " IS NOT NULL) THEN " . $primary . " ELSE " . $secondary ." END ";
	}

    public function findByIdWithType($id) {
        return $this
                  ->createQueryBuilder('it')
                  ->join('it.type', 't')
                  ->where('it.id = :id')
                  ->getQuery()
                  ->setParameter('id', $id)
                  ->getResult();
              ;
    }

    public function findBySlot($slot, $locale) {
        return $this
                  ->createQueryBuilder('se')
                  ->select(array('se.id', 'se.name' . ucfirst($locale) . ' as name'))
                  ->join('se.type', 't')
                  ->where('t.slot = :slot')
                  ->getQuery()
                  ->setParameter('slot', $slot)
                  ->setResultCacheDriver(new \Doctrine\Common\Cache\FilesystemCache('../app/cache/'))
                  ->useResultCache(true, 3600)
                  ->getArrayResult();
              ;
    }
}
