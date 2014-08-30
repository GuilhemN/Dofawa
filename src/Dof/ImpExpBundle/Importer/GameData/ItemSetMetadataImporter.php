<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\ItemsBundle\Entity\ItemSet;

class ItemSetMetadataImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_set_metadatas';
    const BETA_DATA_SET = 'beta_item_set_metadatas';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt = $conn->query('SELECT si.id, MAX(i.level) as level, COUNT(i.id) as count
        FROM ' . $db . '.D2O_ItemSet_item si JOIN ' . $db . '.D2O_Item i
        on si.value = i.id GROUP BY si.id;');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofItemsBundle:ItemSet');
        foreach ($all as $row) {
            $set = $repo->find($row['id']);
            if ($set === null || ($set->isPreliminary() ^ $beta))
                continue;

            $set->setLevel($row['level']);
            $set->setItemCount($row['count']);
        }
    }
}
