<?php

namespace Dof\Bundle\ItemBundle\Entity;

/**
 * EquipmentTemplateRepository.
 */
class EquipmentTemplateRepository extends ItemTemplateRepository
{
    protected function queryWithJoins($criteria)
    {
        $criteria = (array) $criteria;

        // Jointure par défaut
        $qb = $this
                  ->createQueryBuilder('i')
                  ->select(array('i', 'cp', 'icp', 's'))
                  ->join('i.set', 's')
                  ->leftjoin('i.components', 'cp')
                  ->leftjoin('cp.component', 'icp')
            ;

        $i = 0;
        // Ajout des critères à la requête
        foreach ($criteria as $k => $v) {
            $i++;
            $qb
                ->andWhere('i.'.$k.' LIKE :filterWord'.$i)
                ->setParameter('filterWord'.$i, $v)
            ;
        }

        return $qb;
    }
}
