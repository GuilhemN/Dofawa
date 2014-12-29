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
                ->select([
                    's.slug',
                    's.name' . ucfirst($locale) . ' as name',
                    's.preliminary',
                    's.release',
                    's.primaryBonus',
                    'MAX(i.level) as level',
                    'COUNT(i.id) as count_items'
                    ])
                ->join('s.items', 'i')
                ->addOrderBy('level', 'desc')
                ->groupBy('s.id')
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

    public function getMinimalId(){
        $query = $this->createQueryBuilder('s');
        $query->select('MIN(s.id) AS min_id');

        return $query->getQuery()->getArrayResult()[0]['min_id'];
    }


    public function findOneWithJoins(array $criteria = array()){
        $qb =
            $this
                ->createQueryBuilder('s')
                ->select(array('s', 'i', 'c', 're', 'rei'))
                ->leftjoin('s.items', 'i')
                ->leftjoin('s.combinations', 'c')
                ->leftjoin('i.components', 're')
                ->leftjoin('re.component', 'rei')
        ;

		$i = 0;
		foreach($criteria as $k => $v){
			$i++;
			$qb
				->andWhere('s.' . $k . ' LIKE :filterWord' . $i)
				->setParameter('filterWord' . $i, $v)
			;
		}

        return $qb
            ->getQuery()
            ->getSingleResult()
            ;
    }
}
