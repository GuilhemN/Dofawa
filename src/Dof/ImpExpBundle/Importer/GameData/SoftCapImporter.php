<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\CharactersBundle\Entity\SoftCap;
use Dof\CharactersBundle\BaseCharacteristic;

class SoftCapImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'breed_soft_caps';
    const BETA_DATA_SET = 'beta_breed_soft_caps';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $caps = [ ];
        $charas = [
            BaseCharacteristic::VITALITY => 'Vitality',
            BaseCharacteristic::WISDOM => 'Wisdom',
            BaseCharacteristic::STRENGTH => 'Strength',
            BaseCharacteristic::INTELLIGENCE => 'Intelligence',
            BaseCharacteristic::CHANCE => 'Chance',
            BaseCharacteristic::AGILITY => 'Agility'
        ];
        foreach ($charas as $charaId => $charaInfix) {
            $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Breed_statPointFor' . $charaInfix . ' o');
            foreach ($stmt->fetchAll() as $row) {
                $id = intval($row['id']);
                $index = intval($row['_index1']);
                $colIdx = intval($row['_index2']);
                $value = intval($row['value']);
                if (!isset($caps[$id]))
                    $caps[$id] = [
                        BaseCharacteristic::VITALITY => [ ],
                        BaseCharacteristic::WISDOM => [ ],
                        BaseCharacteristic::STRENGTH => [ ],
                        BaseCharacteristic::INTELLIGENCE => [ ],
                        BaseCharacteristic::CHANCE => [ ],
                        BaseCharacteristic::AGILITY => [ ]
                    ];
                if (!isset($caps[$id][$charaId][$index]))
                    $caps[$id][$charaId][$index] = [ 1 => 0, 2 => 1, 3 => 1 ];
                $caps[$id][$charaId][$index][$colIdx] = $value;
            }
            $stmt->closeCursor();
        }
        $caps2 = [ ];
        ksort($caps);
        foreach ($caps as $breed => $subcaps) {
            $caps2[$breed] = [ ];
            ksort($subcaps);
            foreach ($subcaps as $chara => $subcaps2) {
                ksort($subcaps2);
                foreach ($subcaps2 as $cap)
                    $caps2[$breed][] = [ 'chara' => $chara, 'min' => $cap[1], 'cost' => $cap[2], 'gain' => $cap[3] ];
            }
        }
        $breedRepo = $this->dm->getRepository('DofCharactersBundle:Breed');
        foreach ($caps2 as $breed => $subcaps) {
            $breed = $breedRepo->find($breed);
            if ($breed === null || ($breed->isPreliminary() ^ $beta))
                continue;
            $breedCaps = iterator_to_array($breed->getSoftCaps());
            $subLen = count($subcaps);
            $brdLen = count($breedCaps);
            $minLen = min($subLen, $brdLen);
            $i = 0;
            for (; $i < $minLen; ++$i) {
                $row = $subcaps[$i];
                $cap = $breedCaps[$i];
                $cap->setCharacteristic($row['chara']);
                $cap->setMin($row['min']);
                $cap->setCost($row['cost']);
                $cap->setGain($row['gain']);
                $this->dm->persist($cap);
            }
            for (; $i < $subLen; ++$i) {
                $row = $subcaps[$i];
                $cap = new SoftCap();
                $cap->setBreed($breed);
                $cap->setCharacteristic($row['chara']);
                $cap->setMin($row['min']);
                $cap->setCost($row['cost']);
                $cap->setGain($row['gain']);
                $this->dm->persist($cap);
            }
            for (; $i < $brdLen; ++$i)
                $this->dm->remove($breedCaps[$i]);
        }
    }
}
