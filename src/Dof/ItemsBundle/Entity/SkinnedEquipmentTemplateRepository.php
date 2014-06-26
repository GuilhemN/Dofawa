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
            $skins[$skin->getId()] = $skin;
        ksort($skins);
        return $skins;
    }
}
