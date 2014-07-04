<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

class ItemSetImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_sets';
    const BETA_DATA_SET = 'beta_item_sets';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofItemsBundle:ItemSet s SET s.deprecated = true')->execute();

    }
}
