<?php

namespace Dof\ImpExpBundle\Importer\GameData\Item;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\ItemsBundle\Entity\ItemType;

class ItemTypeImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_types';
    const BETA_DATA_SET = 'beta_item_types';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofItemsBundle:ItemType s SET s.deprecated = true')->execute();
        $stmt = $conn->query('SELECT o.*' .
            $this->generateD2ISelects('name', $locales) .
            ' FROM ' . $db . '.D2O_ItemType o' .
            $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofItemsBundle:ItemType');
        foreach ($all as $row) {
            $type = $repo->find($row['id']);
            if ($type === null) {
                $type = new ItemType();
                $type->setDeprecated(true);
                $type->setId($row['id']);
            }
            if ($type->isDeprecated()) {
                $type->setDeprecated(false);
                if (!$type->getRelease())
                    $type->setRelease($release);
                $type->setPreliminary($beta);
                $this->copyI18NProperty($type, 'setName', $row, 'name');
                $type->setSlot($row['superTypeId']);
                $type->setEffectArea(($row['rawZone'] === 'null') ? null : $row['rawZone']);
                $this->dm->persist($type);
            }
        }
    }
}
