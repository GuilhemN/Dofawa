<?php

namespace Dof\ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dof\UserBundle\Entity\User;
use Dof\ForumBundle\Entity\Forum;

/**
 * ForumRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForumRepository extends EntityRepository
{
	function getOrderByDate($criteria = array())
	{
		$criteria = (array) $criteria;

		$qb = $this->createQueryBuilder('f')
				->select('f', 't', 'm')
				->leftjoin('f.topics', 't')
				->leftjoin('t.messages', 'm')
                ->addOrderBy('m.createdAt', 'desc');

		$i = 0;
		// Ajout des critères à la requête
		foreach($criteria as $k => $v){
			$i++;
			$qb
				->andWhere('f.' . $k . ' LIKE :filterWord' . $i)
				->setParameter('filterWord' . $i, $v)
			;
		}

		return $qb->getQuery()
        	->getSingleResult();
	}
 
	function isReadByRepo(Forum $forum, User $user)
	{
		$qb = $this->createQueryBuilder('f')
		  		->select('COUNT(f)')
				->join('f.topics', 't')
				->join('t.readBy', 'r')
				->where('r.id = :user')
				->andWhere('f.id = :forum')
				->setParameters(array('user' => $user->getId(), 'forum' => $forum->getId()))
				->getQuery()->getResult();
			
		if(!empty($qb))
			return true;

		return false;
	}
}
