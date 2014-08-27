<?php

namespace Dof\ItemsBundle\Entity;

/**
 * EquipmentTemplateRepository
 */
class EquipmentTemplateRepository extends ItemTemplateRepository
{
    protected function queryWithJoins($criteria){
		$criteria = (array) $criteria;

		// Select par défaut
		$select = array('i', 'cp', 'icp', 's');
		// Jointure par défaut
        $qb = $this
                  ->createQueryBuilder('i')
                  ->join('i.components', 'cp')
				  ->join('cp.component', 'icp')
                  ->join('i.set', 's')
			;
		}

		// Transmission des jointures à récupérées
		$qb->select($select);

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
