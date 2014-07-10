<?php

namespace Dof\ItemsBundle\Entity;

/**
 * SkinnedEquipmentTemplateRepository
 */
class SkinnedEquipmentTemplateRepository extends EquipmentTemplateRepository
{
    public function findBySkinIds(array $skinIds)
    {
        $skins = array();
        foreach ($this->createQueryBuilder('e')
            ->where('e.skin IN (:skinIds)')
            ->setParameter('skinIds', $skinIds)
            ->getQuery()
            ->getResult() as $skin)
            $skins[$skin->getSkin()] = $skin;
        ksort($skins);
        return $skins;
    }

    public function findBySlot($slot, $locale) {
        return $this
                  ->createQueryBuilder('se')
                  ->select(array('se.id', 'se.name' . ucfirst($locale)))
                  ->join('se.type', 't')
                  ->where('t.slot = :slot AND se.skin IS NOT NULL')
                  ->getQuery()
                  ->setParameter('slot', $slot)
                  ->getResult()
              ;
    }

}
