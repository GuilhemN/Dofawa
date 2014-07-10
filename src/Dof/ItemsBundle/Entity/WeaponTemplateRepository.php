<?php

namespace Dof\ItemsBundle\Entity;

/**
 * WeaponTemplateRepository
 */
class WeaponTemplateRepository extends SkinnedEquipmentTemplateRepository
{

    public function hasBone($format = 'normal') {
        $req = $this->createQueryBuilder('w');

        if($format == 'json')
          $req->select(array('id','nom'));

        $req
            ->where('w.bone IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
