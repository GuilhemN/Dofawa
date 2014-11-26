<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ItemsBundle\Entity\ItemTemplateEffect;
use Dof\ItemsBundle\Entity\WeaponDamageRow;

use XN\Persistence\CollectionSynchronizationHelper;

use Dof\ItemsBundle\EffectListHelper;

class ItemEffectImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_template_effects';
    const BETA_DATA_SET = 'beta_item_template_effects';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $items = [ ];
        $stmt = $conn->query('SELECT o.id, o._index1, o.effectId, o.diceNum, o.diceSide, o.value FROM ' . $db . '.D2O_Item_possibleEffect o');
        foreach ($stmt->fetchAll() as $row) {
            $itemId = intval($row['id']);
            $order = intval($row['_index1']);
            if (!isset($items[$itemId]))
                $items[$itemId] = [ ];
            $items[$itemId][$order] = [
                'order' => $order,
                'type' => $row['effectId'],
                'param1' => $row['diceNum'],
                'param2' => $row['diceSide'],
                'param3' => $row['value']
            ];
        }
        $stmt->closeCursor();
        ksort($items);
        $itemRepo = $this->dm->getRepository('DofItemsBundle:ItemTemplate');
        $effectRepo = $this->dm->getRepository('DofCharactersBundle:EffectTemplate');
        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($items));
        foreach ($items as $item => $effects) {
            $item = $itemRepo->find($item);
            if ($item === null || ($item->isPreliminary() ^ $beta))
                continue;
            ksort($effects);
            if ($item->isEquipment()) {
                $charas = EffectListHelper::extractCharacteristicsRanges($effects);
                $item->setCharacteristics($charas, true);
                if ($item->isWeapon()) {
                    $damages = EffectListHelper::extractWeaponDamageRows($effects);
                    CollectionSynchronizationHelper::synchronize($this->dm, $item->getDamageRows()->toArray(), $damages, function () use ($item) {
                        $wdr = new WeaponDamageRow();
                        $wdr->setWeapon($item);
                        return $wdr;
                    }, function ($wdr, $row) {
                        $wdr->setOrder($row['order']);
                        $wdr->setElement($row['element']);
                        $wdr->setMin($row['min']);
                        $wdr->setMax($row['max']);
                        $wdr->setLeech($row['leech']);
                    });
                }
            } else
                $effects = array_values($effects);
            CollectionSynchronizationHelper::synchronize($this->dm, $item->getEffects()->toArray(), $effects, function () use ($item) {
                $fx = new ItemTemplateEffect();
                $fx->setItem($item);
                return $fx;
            }, function ($fx, $row) use ($effectRepo) {
                $fx->setOrder($row['order']);
                $fx->setEffectTemplate($effectRepo->find($row['type']));
                $fx->setRawParam1($row['param1']);
                $fx->setRawParam2($row['param2']);
                $fx->setRawParam3($row['param3']);
            });
            ++$rowsProcessed;
            if (($rowsProcessed % 300) == 0) {
                $this->dm->flush();
                $this->dm->clear();
                if ($output && $progress)
                    $progress->advance(300);
            }
        }
        if ($output && $progress)
            $progress->finish();
    }
}
