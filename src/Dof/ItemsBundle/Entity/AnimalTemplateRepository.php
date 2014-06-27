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
            ->getResult() as $bone)
            $bones[$bone->getId()] = $bone;
        ksort($bones);
        return $bones;
    }
}
