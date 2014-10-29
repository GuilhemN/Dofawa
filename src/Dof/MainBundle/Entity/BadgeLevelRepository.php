<?php

namespace Dof\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dof\UserBundle\Entity\User;

/**
 * BadgeLevelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BadgeLevelRepository extends EntityRepository
{
	public function getBadgeUser(User $user)
	{
		return $this
                  ->createQueryBuilder('l')
                  ->select('l', 'b', 'ub')
                  ->join('l.badge', 'b')
                  ->join('b.userBadges', 'ub')
                  ->where('ub.owner = :user')
                  ->getQuery()
                  ->setParameter('user', $user)
                  ->getResult();
              ;
	}
}
