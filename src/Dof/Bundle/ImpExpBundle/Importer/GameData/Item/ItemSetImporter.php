<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Item;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\ItemBundle\Entity\ItemSet;

class ItemSetImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_sets';
    const BETA_DATA_SET = 'beta_item_sets';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write) {
            $this->dm->createQuery('UPDATE DofItemBundle:ItemSet s SET s.deprecated = true')->execute();
        }
        $stmt = $conn->query('SELECT o.*'.
            $this->generateD2ISelects('name', $locales).
            ' FROM '.$db.'.D2O_ItemSet o'.
            $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofItemBundle:ItemSet');
        foreach ($all as $row) {
            $set = $repo->find($row['id']);
            if ($set === null) {
                $set = new ItemSet();
                $set->setDeprecated(true);
                $set->setId($row['id']);
            }
            if ($set->isDeprecated()) {
                $set->setDeprecated(false);
                if (!$set->getRelease()) {
                    $set->setRelease($release);
                }
                $set->setPreliminary($beta);
                $this->copyI18NProperty($set, 'name', $row, 'name');
                $this->dm->persist($set);
            }
        }
    }
}
