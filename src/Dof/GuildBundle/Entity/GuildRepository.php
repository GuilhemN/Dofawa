<?php

namespace Dof\GuildBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GuildRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GuildRepository extends EntityRepository
{
	public function findGuildsWithLimits($firstresult = 0, $maxresults=10)
	{
		$qb = $this->createQueryBuilder('g');

		$qb->orderBy('g.name', 'ASC')
		   ->setFirstResult( $firstresult )
		   ->setMaxResults( $maxresults );

		return $qb->getQuery()->getResult();
	}
	public function count()
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
