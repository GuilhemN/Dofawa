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


    public function hasBone($format = 'normal') {
        $req = $this->createQueryBuilder('a');

        return $req
            ->where('a.bone IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
