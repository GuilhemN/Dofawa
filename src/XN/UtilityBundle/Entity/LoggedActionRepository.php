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
            ->createQueryBuilder('a');
        $qb
            ->where($qb->expr()->eq('a.owner', ':user'))
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->groupBy('a.key')
            ->setMaxResults($max)
          ;
        return $qb->getQuery()->getResult();
    }

    // TODO : Specific to Dofawa
    public function findLastCharacter(AdvancedUserInterface $user) {
        $qb = $this
            ->createQueryBuilder('a');
        $e = $qb->expr();
        $qb
            ->join('DofBuildBundle:Stuff', 's', Join::WITH, 'a.classId = s.id')
            ->join('s.character', 'c')
            ->where(
                $e->andX(
                    $e->eq('a.key', $e->literal('build')),
                    $e->andX(
                        $e->eq('a.owner', ':user'),
                        $e->eq('c.owner', 'a.owner')
                    )
                )
            )
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->groupBy('a.key')
            ->setMaxResults(1);
        ;
        return ($result = $qb->getQuery()->getOneOrNullResult()) !== null ? $result->getEntity() : null;
    }
}
