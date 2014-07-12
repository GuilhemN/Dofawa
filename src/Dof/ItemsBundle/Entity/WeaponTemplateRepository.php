<?php

namespace Dof\ItemsBundle\Entity;

/**
 * WeaponTemplateRepository
 */
class WeaponTemplateRepository extends SkinnedEquipmentTemplateRepository
{
    public function hasSkin($format = 'normal', $locale = 'fr') {
        $req = $this->createQueryBuilder('w');

        if($format == 'json')
          $req->select(array('w.id', 'w.name' . ucfirst($locale) . ' as name'));

        return $req
            ->where('w.skin IS NOT NULL and w.ethereal = 0')
            ->getQuery()
            ->setResultCacheDriver(new \Doctrine\Common\Cache\FilesystemCache('../app/cache/'))
            ->useResultCache(true, 3600)
            ->getArrayResult();
        ;
    }
}
