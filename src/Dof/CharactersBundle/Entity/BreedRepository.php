<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BreedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BreedRepository extends EntityRepository
{
    public function findBySkinIds(array $skinIds)
    {
        $skins = array();
        foreach ($this->createQueryBuilder('b')
            ->where('b.maleSkin IN (:skinIds)')
            ->orWhere('b.maleLodefSkin IN (:skinIds)')
            ->orWhere('b.femaleSkin IN (:skinIds)')
            ->orWhere('b.femaleLodefSkin IN (:skinIds)')
            ->setParameter('skinIds', $skinIds)
            ->getQuery()
            ->getResult() as $skin) {
            $skins[$skin->getMaleSkin()] = $skin;
            $skins[$skin->getMaleLodefSkin()] = $skin;
            $skins[$skin->getFemaleSkin()] = $skin;
            $skins[$skin->getFemaleLodefSkin()] = $skin;
        }
        $skins = array_intersect_key($skins, array_flip($skinIds));
        ksort($skins);
        return $skins;
    }
    
    public function findRelation() {
        return $this
                  ->createQueryBuilder('b')
                  ->select(array('b.id', 'b.slug'))
                  ->getQuery()
                  ->setResultCacheDriver(new \Doctrine\Common\Cache\FilesystemCache('../app/cache/'))
                  ->useResultCache(true, 3600)
                  ->getArrayResult();
              ;
    }
}
