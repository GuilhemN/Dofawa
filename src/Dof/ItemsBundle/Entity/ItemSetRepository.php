<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ItemSetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemSetRepository extends EntityRepository
{
    public function findOneWithJoins($criteria){
        $qb = $this->queryWithJoins($criteria);

		return $qb
        	->getQuery()
        	->getSingleResult()
		;
    }

    public function findWithJoins($criteria){
        $qb = $this->queryWithJoins($criteria);

		return $qb
        	->getQuery()
        	->getResult()
		;
    }

    protected function queryWithJoins($criteria){
        $criteria = (array) $criteria;

        $qb = $this
                  ->createQueryBuilder('s')
				  ->select(array('s', 'i', 'c'))
                  ->join('s.items', 'i')
                  ->join('s.combinations', 'c')
                  ->orderBy('c.itemCount', 'asc')
              ;

		$i = 0;
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
