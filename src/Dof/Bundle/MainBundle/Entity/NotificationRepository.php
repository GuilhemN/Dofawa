<?php

namespace Dof\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dof\Bundle\UserBundle\Entity\User;

/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends EntityRepository
{

    public function countUnread(User $user = null)
    {
        return $this->createQueryBuilder('id')
            ->select('COUNT(id)')
            ->where('id.owner = :owner and id.isRead = false')
            ->setParameter('owner', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function delOldPetsNotifications(){
        $interval = new \DateTime();
        $interval->modify('-1 week');

        $count = $this->createQueryBuilder('id')
            ->select('COUNT(id)')
            ->where('id.createdAt < :interval and id.isRead = true and id.type = :type')
            ->setParameters(array('interval' => $interval, 'type' => 'pets.hungry'))
            ->getQuery()
            ->getSingleScalarResult()
        ;
        
        $this->createQueryBuilder('id')
            ->delete()
            ->where('id.createdAt < :interval and id.isRead = true and id.type = :type')
            ->setParameters(array('interval' => $interval, 'type' => 'pets.hungry'))
            ->getQuery()
            ->execute()
        ;
        return $count; 
    }
}