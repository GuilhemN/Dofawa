<?php

namespace Dof\ImpExpBundle\Importer\GameData\Item;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;

use Dof\ItemBundle\Entity\ItemSetCombination;
use Dof\ItemBundle\Entity\ItemSetEffect;

use XN\Persistence\CollectionSynchronizationHelper;

use Dof\ItemBundle\EffectListHelper;

class ItemSetEffectImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_set_effects';
    const BETA_DATA_SET = 'beta_item_set_effects';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $sets = [ ];
        $stmt = $conn->query('SELECT o.id, o._index1, o._index2, o.effectId, o.diceNum, o.diceSide, o.value
            FROM ' . $db . '.D2O_ItemSet_effect o
            WHERE o.effectId IN (SELECT id FROM ' . $db . '.D2O_Effect)');
        foreach ($stmt->fetchAll() as $row) {
            $setId = intval($row['id']);
            $itemCount = intval($row['_index1']);
            $order = intval($row['_index2']);
            if (!isset($sets[$setId]))
                $sets[$setId] = [ ];
            if (!isset($sets[$setId][$itemCount]))
                $sets[$setId][$itemCount] = [ ];
            $sets[$setId][$itemCount][$order] = [
                'order' => $order,
                'type' => $row['effectId'],
                'param1' => $row['diceNum'],
                'param2' => $row['diceSide'],
                'param3' => $row['value']
            ];
        }
        $stmt->closeCursor();
        ksort($sets);
        $setRepo = $this->dm->getRepository('DofItemBundle:ItemSet');
        $effectRepo = $this->dm->getRepository('DofCharactersBundle:EffectTemplate');
        foreach ($sets as $set => $combos) {
            $set = $setRepo->find($set);
            if ($set === null || ($set->isPreliminary() ^ $beta))
                continue;
            ksort($combos);
            CollectionSynchronizationHelper::synchronize($this->dm, $set->getCombinations()->toArray(), array_keys($combos), function () use ($set) {
                $combo = new ItemSetCombination();
                $combo->setSet($set);
                return $combo;
            }, function ($combo, $row) use ($combos, $effectRepo) {
                $combo->setItemCount($row);
                $effects = $combos[$row];
                ksort($effects);
                $charas = EffectListHelper::extractCharacteristics($effects);
                $combo->setCharacteristics($charas, true);
                CollectionSynchronizationHelper::synchronize($this->dm, $combo->getEffects()->toArray(), $effects, function () use ($combo) {
                    $fx = new ItemSetEffect();
                    $fx->setCombination($combo);
                    return $fx;
                }, function ($fx, $row) use ($effectRepo) {
                    $fx->setOrder($row['order']);
                    $effect = $effectRepo->find($row['type']);
                    $fx->setEffectTemplate($effect);
                    $fx->setParam1($row['param1']);
                    $fx->setParam2($row['param2']);
                    $fx->setParam3($row['param3']);
                });
            });
        }
    }
}
