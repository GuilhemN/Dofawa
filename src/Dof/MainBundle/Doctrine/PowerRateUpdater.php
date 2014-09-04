<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Dof\ItemsBundle\Entity\EquipmentTemplate;

class PowerRateUpdater
{

	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof EquipmentTemplate) {
			$ent->setPowerRate($this->calcPowerRate($ent->getCharacteristics));
		}
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		$mds = array();
		$updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow) {
			return $ent instanceof EquipmentTemplate && self::hasCharactsChanges($ent, $uow->getEntityChangeSet($ent));
		});
		foreach ($updates as $ent) {
			$ent->setPowerRate($this->calcPowerRate($ent->getCharacteristics));

			$clazz = get_class($ent);
			if (isset($mds[$clazz]))
				$md = $mds[$clazz];
			else {
				$md = $em->getClassMetadata($clazz);
				$mds[$clazz] = $md;
			}
			$uow->recomputeSingleEntityChangeSet($md, $ent);
		}
	}

    protected function hasCharactsChanges($entity, $chgset){
		$metadata = $this->getPowerRate();
        $charactsFields = array();
        foreach(array_keys($metadata) as $charact){
            // $charactsFields[ ] = $charact;
			$charactsFields[ ] = 'max' . ucfirst($charact);
			$charactsFields[ ] = 'min' . ucfirst($charact);
		}

		foreach ($chgset as $key => $value)
			if (in_array($key, $charactsFields))
				return true;
		return false;
	}

    protected function calcPowerRate(array $characts, $range = true){
        $powerRates = $this->getPowerRate();

        $pwrg = 0; // Power-Rate Global
        foreach($powerRates as $charact => $powerRate){
            $row = $characts[$charact];
            if($range)
                $pwrg += ($row['min'] + $row['max']) / 2 * $powerRate;
            else
                $pwrg += $row * $powerRate;
        }

        return $pwrg;
    }

    protected function getPowerRate() {
        return [
            // Caracts
            'vitality' => 0.25,
            'strength' => 1,
            'intelligence' => 1,
            'chance' => 1,
            'agility' => 1,
            'wisdom' => 3,
            'initiative' => 0.1,
            'prospecting' => 3,
            'power' => 2,

            //Résistances
            'neutralResistance' => 2,
            'earthResistance' => 2,
            'fireResistance' => 2,
            'waterResistance' => 2,
            'airResistance' => 2,
            'percentNeutralResistance' => 6,
            'percentEarthResistance' => 6,
            'percentFireResistance' => 6,
            'percentWaterResistance' => 6,
            'percentAirResistance' => 6,
            'pushbackResistance' => 2,
            'criticalResistance' => 2,
            'apLossResistance' => 7,
            'mpLossResistance' => 7,

            // Bonus
            'trapPower' => 2,
            'trapDamage' => 15,
            'lock' => 4,
            'dodge' => 4,
            'apReduction' => 7,
            'mpReduction' => 7,
            'heals' => 20,
            'criticalHits' => 30,
            'summons' => 30,
            'reflectedDamage' => 30,
            'range' => 51,
            'mp' => 90,
            'ap' => 100,

            // Dommages
            'damage' => 20,
            'neutralDamage' => 5,
            'earthDamage' => 5,
            'fireDamage' => 5,
            'waterDamage' => 5,
            'airDamage' => 5
            ];
    }
}
