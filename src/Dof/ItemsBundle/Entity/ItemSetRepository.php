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
    public function findOneWithJoins($criteria = array()){
        $qb = $this->queryWithJoins($criteria);

		return $qb
        	->getQuery()
        	->getSingleResult()
		;
    }

    public function findWithJoins($criteria = array(), $type = 'normal'){
        $qb = $this->queryWithJoins($criteria, $type);

		return $qb
        	->getQuery()
        	->getResult()
		;
    }

    protected function queryWithJoins($criteria){
        $criteria = (array) $criteria;

        if($type == 'normal')
            $qb = $this
                      ->createQueryBuilder('s')
    				  ->select(array('s', 'i', 'c', 're', 'rei'))
                      ->join('s.items', 'i')
                      ->join('s.combinations', 'c')
                      ->leftjoin('i.components', 're')
                      ->leftjoin('re.component', 'rei')
                      ->orderBy('c.itemCount', 'asc')
                  ;
        elseif($type == 'list')
            $qb = $this
                      ->createQueryBuilder('s')
    				  ->select(array('s', 'i', 'c'))
                      ->join('s.items', 'i')
                      ->join('s.combinations', 'c')
                      ->orderBy('c.itemCount', 'asc')
                  ;
        else
            throw new Exception('Unknow type in ' . __FILE__);


		$i = 0;
		foreach($criteria as $k => $v){
			$i++;
			$qb
				->andWhere('s.' . $k . ' LIKE :filterWord' . $i)
				->setParameter('filterWord' . $i, $v)
			;
		}

        return $qb;
    }
}
