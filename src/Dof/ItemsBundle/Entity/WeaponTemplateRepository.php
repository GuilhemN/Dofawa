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
          $req->select(array('w.id', 'w.name_'.$locale));

        $req
            ->where('w.bone IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
