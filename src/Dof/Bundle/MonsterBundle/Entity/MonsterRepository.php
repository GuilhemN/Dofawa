<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MonsterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MonsterRepository extends EntityRepository
{
    public function findWithOptions($options = array(), array $orders = array(), $limit = null, $offset = null, $locale = 'fr', $type = 'normal', $full = false) {
        $options = (array) $options;
        $qb = $this->createQueryBuilder('m');

        if($type == 'count')
            $qb->select(array('COUNT(m)'));

        if(!empty($options['name']))
            $qb
            ->andWhere('m.name' . ucfirst($locale).' LIKE :name')
            ->setParameter('name', '%' . $options['name'] . '%')
            ;
        if(!empty($options['update']))
            $qb
                ->andWhere('m.release LIKE :release')
                ->setParameter('release', $options['update'] . '%')
            ;
        $qb
            ->andWhere('m.deprecated = false')
            ->orderBy('m.name' . ucfirst($locale))
        ;

        foreach($orders as $column => $order)
            $qb->addOrderBy('m.' . $column, $order);

        if($type == 'count')
            return $qb
                ->getQuery()
                ->getSingleScalarResult();

        else
            return $qb
                ->getQuery()
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getResult();
                  ;
    }

	public function countWithOptions($options = array(), $locale = 'fr'){
		return $this->findWithOptions($options, array(), null, null, $locale, 'count');
	}
}