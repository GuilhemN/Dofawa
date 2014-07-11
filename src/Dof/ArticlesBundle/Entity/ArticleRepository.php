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
	public function findArticlesWithLimits($is_news = false, $firstresult = 0, $maxresults=10)
	{
		if($is_news)
			$operator = ' = ';
		else
			$operator = ' != ';

		$qb = $this->createQueryBuilder('a');

		$qb->add('where', 'a.type'.$operator.ArticleType::NEWS .
					' and a.published=1')
	  	 ->add('orderBy', 'a.createdAt DESC, a.id DESC')
			 //->setParameter('boolean', $boolean)
			 ->setFirstResult( $firstresult )
			 ->setMaxResults( $maxresults );

		$result = $qb->getQuery()
	  						 ->setResultCacheDriver(new \Doctrine\Common\Cache\FilesystemCache('../app/cache/'))
								 ->useResultCache(true, 3600, 'findArticlesWithLimits'.$firstresult.'-'.$maxresults)
								 ->getResult();

	  return $result;
	}

	/**
    * Count all article
    *
    * @return integer
    */
    public function countTotalNews(){
      return $this->createQueryBuilder('a')
     ->select('COUNT(a)')
		 ->where('a.published=1 and a.type = '.ArticleType::NEWS)
     ->getQuery()
     ->getSingleScalarResult();
    }
}
