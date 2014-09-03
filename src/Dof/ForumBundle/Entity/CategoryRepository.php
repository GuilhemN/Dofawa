<?php

namespace Dof\ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dof\ForumBundle\Entity\Forum;
/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
	function displayOrder()
	{
		$qb = $this->createQueryBuilder('c')
				->leftJoin('c.forums', 'f')
                ->addOrderBy('c.index', 'ASC')
                ->addOrderBy('f.index', 'ASC');

		return $qb;
	}
}
