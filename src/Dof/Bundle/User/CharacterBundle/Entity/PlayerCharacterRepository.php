<?php

namespace Dof\Bundle\User\CharacterBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlayerCharacterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerCharacterRepository extends EntityRepository
{
    public function findByUser($user) {
        return $this
                  ->createQueryBuilder('pc')
                  ->select(array('pc', 's', 'l'))
                  ->join('pc.stuffs', 's')
                  ->join('s.look', 'l')
                  ->where('pc.owner=:user')
                  ->getQuery()
                  ->setParameter('user', $user)
                  ->getResult();
              ;
    }

    public function findForShow($user, $perso) {
        return $this
                  ->createQueryBuilder('pc')
                  ->select(array('pc'))
                  ->join('pc.owner', 'o')
                  ->where('o.slug = :user and pc.slug = :perso')
                  ->getQuery()
                  ->setParameter('user', $user)
                  ->setParameter('perso', $perso)
                  ->getResult()[0]
              ;
    }
}