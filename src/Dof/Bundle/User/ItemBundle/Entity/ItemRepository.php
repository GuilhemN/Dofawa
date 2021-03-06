<?php

namespace Dof\Bundle\User\ItemBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dof\Bundle\UserBundle\Entity\User;

/**
 * ItemRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemRepository extends EntityRepository
{
    public function findWithOptions($options = array(), User $user, array $orders = array(), $limit = null, $offset = null, $locale = 'fr', $type = 'normal', $full = false)
    {
        $options = (array) $options;
        $qb = $this->createQueryBuilder('i');

        if ($type == 'count') {
            $qb->select(array('COUNT(i)'));
        }

        $qb
            ->join('i.itemTemplate', 'it')
            ->join('it.type', 't')
            ->addOrderBy('it.level', 'DESC')
            ->andWhere('i.owner = (:user)')
            ->setParameter('user', $user)
        ;

        if (!empty($options['name'])) {
            $qb
            ->andWhere('it.name LIKE :name or i.name LIKE :name')
            ->setParameter('name', '%'.$options['name'].'%')
            ;
        }
        if (isset($options['type'])) {
            $qb
                ->andWhere('t.slot IN (:slot)')
                ->setParameter('slot', $options['type'])
            ;
        }
        if (!empty($options['maj'])) {
            $qb
                ->andWhere('it.release LIKE :release')
                ->setParameter('release', '%'.$options['maj'].'%')
            ;
        }

        foreach ($orders as $column => $order) {
            $qb->addOrderBy('it.'.$column, $order);
        }

        if ($type == 'count') {
            return $qb
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            return $qb
                ->getQuery()
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getResult();
        }
    }

    public function countWithOptions($options = array(), User $user, $locale = 'fr')
    {
        return $this->findWithOptions($options, $user, array(), null, null, $locale, 'count');
    }
}
