<?php

namespace Dof\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProgrammedNotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProgrammedNotificationRepository extends EntityRepository
{
    public function findReadyNotifications(\Datetime $date = null) {
        if($date === null)
            $date = new \Datetime;

        return $this
              ->createQueryBuilder('n')
              ->where('n.date < :date and n.created = false')
              ->getQuery()
              ->setParameter('date', $date)
              ->getResult();
          ;
    }
}