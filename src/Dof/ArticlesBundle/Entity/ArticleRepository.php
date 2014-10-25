<?php

namespace Dof\ArticlesBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Dof\ArticlesBundle\ArticleType;

/**
 * ArticlesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
	public function findArticlesWithLimits($type = 4, $firstresult = 0, $maxresults= 10, $published = 1)
	{
		
		$qb = $this->createQueryBuilder('a');

		$qb
			->add('where', 'a.type ='.$type. 'and a.published='.$published.' and a.archive= 0')
	  		->add('orderBy', 'a.createdAt DESC, a.id DESC')
			->setFirstResult( $firstresult )
			->setMaxResults( $maxresults );

		return $qb
			->getQuery()
			->setResultCacheDriver(new \Doctrine\Common\Cache\FilesystemCache('../app/cache/'))
			->useResultCache(true, 3600)
			->getResult();
	}

	/**
    * Count all article
    *
    * @return integer
    */
    public function countTotal($type = 4, $published = 1){

		return $this->createQueryBuilder('a')
		    ->select('COUNT(a)')
			->where('a.published='.$published.' and a.type =' . $type.' and a.archive= 0')
		    ->getQuery()
		    ->getSingleScalarResult();
    }
}
