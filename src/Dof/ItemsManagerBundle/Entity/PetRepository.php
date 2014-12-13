<?php

namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Dof\UserBundle\Entity\User;

/**
* PetRepository
*
* This class was generated by the Doctrine ORM. Add your own custom
* repository methods below.
*/
class PetRepository extends EntityRepository
{
	public function getRaisablePets(User $user){
		$qb = $this->createQueryBuilder('i')
				   ->select('i')
				   ->where('i.raise = true and i.owner = :user')
				   ->setParameter('user', $user)
				   ->addOrderBy('i.nextMeal', 'ASC')
		;

		return $qb->getQuery()->getResult();
	}

	public function getAllPetsNotification(){
		$qb = $this->createQueryBuilder('i')
				   ->select('i', 'u', 'it')
				   ->join('i.itemTemplate','it')
				   ->join('i.owner','u')
				   ->where('i.raise = true')
				   ->andWhere('u.petsManagerNotifications = true')
				   ->andWhere('i.nextMeal < CURRENT_TIMESTAMP() and i.nextMeal > i.lastNotification')
		;

		return $qb->getQuery()->getResult();
	}
}
