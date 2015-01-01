<?php

namespace Dof\QuestBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * QuestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestRepository extends EntityRepository
{
    public function findAllWithArticles($locale = 'fr') {
        return $this
        ->createQueryBuilder('q')
        ->select(['q', 'a'])
        ->join('q.article', 'a')
        ->orderBy('q.name' . ucfirst($locale), 'ASC')
        ->getQuery()
        ->getResult();
        ;
    }
}
