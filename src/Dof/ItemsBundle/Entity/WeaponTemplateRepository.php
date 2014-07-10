<?php

namespace Dof\ItemsBundle\Entity;

/**
 * WeaponTemplateRepository
 */
class WeaponTemplateRepository extends SkinnedEquipmentTemplateRepository
{
    public function hasBone($format = 'normal', $locale = 'fr') {
        $req = $this->createQueryBuilder('w');

        if($format == 'json')
          $req->select(array('w.id', 'w.name' . ucfirst($locale));

        return $req
            ->where('w.skin IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
