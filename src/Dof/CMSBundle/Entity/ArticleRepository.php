<?php

namespace Dof\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
	public function findArticlesWithLimits($class = 'DofCMSBundle:Article', $firstresult = 0, $maxresults= 10, $published = 1)
	{

		$qb = $this->createQueryBuilder('a');

		$qb
			->where('a INSTANCE OF :class and a.published = :published')
			->setParameter('class', $class)
			->setParameter('published', (bool) $published)
	  		->addOrderBy('a.createdAt', 'DESC')
			->addOrderBy('a.id', 'DESC')
			->setFirstResult($firstresult)
			->setMaxResults($maxresults);

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
    public function countTotal($class = 'DofCMSBundle:Article', $published = 1){

		return $this->createQueryBuilder('a')
		    ->select('COUNT(a)')
			->where('a INSTANCEOF :class and a.published = :published')
			->setParameter('class', $class)
			->setParameter('published', (bool) $published)
		    ->getQuery()
		    ->getSingleScalarResult();
    }
}
