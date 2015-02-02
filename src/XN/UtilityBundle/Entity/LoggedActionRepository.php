<?php

namespace XN\UtilityBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * LoggedActionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LoggedActionRepository extends EntityRepository
{
    public function findLastActions(AdvancedUserInterface $user, $max = 20) {
        $qb = $this
            ->createQueryBuilder('a')
            ->where($qb->expr()->eq('a.owner', ':user'))
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->groupBy('a.key')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
          ;
        return $qb;
    }

    // TODO : Specific to Dofawa
    public function findLastCharacter(AdvancedUserInterface $user) {
        $qb = $this
            ->createQueryBuilder('a');
        $e = $qb->expr();
        $qb
            ->join('DofBuildBundle:PlayerCharacter', 'c', Join::WITH, 'a.classId = c.id and a.owner = c.owner')
            ->where(
                $e->andX(
                    $e->eq('a.key', $e->literal('build')),
                    $e->eq('a.owner', ':user')
                )
            )
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->groupBy('a.key')
            ->getQuery()
            ->getOneOrNullResult();
        ;
        return $qb;
    }
}
