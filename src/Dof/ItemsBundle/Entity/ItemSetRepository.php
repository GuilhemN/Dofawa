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
    public function findForSearch($data, $locale = 'fr'){
        $data = (array) $data;
        $qb = $this
                ->createQueryBuilder('s')
                ->addOrderBy('s.level', 'desc')
            ;

        if(!empty($data['name']))
            $qb
                ->andWhere('s.name' . ucfirst($locale) . ' LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%')
            ;
        if(!empty($data['maj']))
            $qb
                ->andWhere('s.release = :release')
                ->setParameter('release', $data['maj'])
            ;

        return $qb
        	->getQuery()
        	->getResult()
        ;
    }

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

    public function getMinimalId(){
        $query = $this->createQueryBuilder('s');
        $query->select('MIN(s.id) AS min_id');

        return $query->getQuery()->getArrayResult()[0]['min_id'];
    }

    protected function queryWithJoins($criteria, $type = 'normal'){
        $criteria = (array) $criteria;

        if($type == 'normal')
            $qb = $this
                      ->createQueryBuilder('s')
    				  ->select(array('s', 'i', 'c', 're', 'rei'))
                      ->leftjoin('s.items', 'i')
                      ->leftjoin('s.combinations', 'c')
                      ->leftjoin('i.components', 're')
                      ->leftjoin('re.component', 'rei')
                      ->addOrderBy('c.itemCount', 'asc')
                  ;
        elseif($type == 'list')
            $qb = $this
                      ->createQueryBuilder('s')
                      ->addOrderBy('s.level', 'desc')
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
