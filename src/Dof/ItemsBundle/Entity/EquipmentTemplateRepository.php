<?php

namespace Dof\ItemsBundle\Entity;

/**
 * EquipmentTemplateRepository
 */
class EquipmentTemplateRepository extends ItemTemplateRepository
{
    protected function queryWithJoins($criteria){
		$criteria = (array) $criteria;

		// Jointure par défaut
        $qb = $this
                  ->createQueryBuilder('i')
                  ->select(array('i', 'cp', 'icp', 's'))
                  ->join('i.components', 'cp')
				  ->join('cp.component', 'icp')
                  ->join('i.set', 's')
			;

		$i = 0;
		// Ajout des critères à la requête
		foreach($criteria as $k => $v){
			$i++;
			$qb
				->andWhere('i.' . $k . ' LIKE :filterWord' . $i)
				->setParameter('filterWord' . $i, $v)
			;
		}

		return $qb;
	}
}
