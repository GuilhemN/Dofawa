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

		return  /*" COALESCE(" . */ $primary /*. ", " . $secondary .") "*/;
	}

    public function findByIdWithType($id) {
        return $this
                  ->createQueryBuilder('i')
                  ->join('i.type', 't')
                  ->where('i.id = :id')
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
                  ->getArrayResult()
              ;
    }

	public function findWithJoins($criteria, $firstResult = null, $maxResults = null){
		$qb = $this->queryWithJoins($criteria);

		if($firstResult != null)
			$qb->setFirstResult($firstResult);
		if($maxResults != null)
			$qb->setMaxResults($maxResults);

		return $qb
        	->getQuery()
        	->getResult()
		;
	}

	public function findOneWithJoins($criteria){
		$qb = $this->findWithJoins($criteria);

		return $qb
			->getQuery()
			->getSingleResult()
		;
	}

	protected function queryWithJoins($criteria){
		$criteria = (array) $criteria;

		// Jointure par défaut
        $qb = $this
                  ->createQueryBuilder('i')
				  ->select(array('i', 'cp'));
                  ->join('i.components', 'cp')
              ;

		$i = 0;
		// Ajout des critères à la requête
		foreach($criteria as $k => $v){
			$i++;
			$qb
				->andWhere('i.' . $k . ' LIKE :filterWord' . $i)
				->setParameter('filterWord' . $i, $v)
			;
		}

		return $qb;
	}
}
