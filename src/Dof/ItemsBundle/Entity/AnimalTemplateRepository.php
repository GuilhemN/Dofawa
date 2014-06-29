<?php

namespace Dof\ItemsBundle\Entity;

/**
 * AnimalTemplateRepository
 */
class AnimalTemplateRepository extends EquipmentTemplateRepository
{
    public function findByBoneIds(array $boneIds)
    {
        $bones = array();
        foreach ($this->createQueryBuilder('a')
            ->where('a.bone IN (:boneIds)')
            ->setParameter('boneIds', $boneIds)
            ->getQuery()
            ->getResult() as $bone) {
            $id = $bone->getId();
            if (!isset($bones[$id]))
                $bones[$id] = [ ];
            $bones[$id][] = $bone;
        }
        ksort($bones);
        return $bones;
    }
}
