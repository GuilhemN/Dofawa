<?php

namespace Dof\ItemsBundle\Entity;

/**
 * WeaponTemplateRepository
 */
class WeaponTemplateRepository extends SkinnedEquipmentTemplateRepository
{

    public function hasBone($format = 'normal') {
        $req = $this->createQueryBuilder('w');

        return $req
            ->where('w.bone IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
