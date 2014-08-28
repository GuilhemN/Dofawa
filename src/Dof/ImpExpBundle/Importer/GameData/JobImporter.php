<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\ItemsBundle\Entity\Job;

class JobImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'job';
    const BETA_DATA_SET = 'beta_job';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt = $conn->query('SELECT o.*' .
            $this->generateD2ISelects('name', $locales) .
            ' FROM ' . $db . '.D2O_Job o' .
            $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofItemsBundle:Job');

        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));

        foreach ($all as $row) {
            $job = $repo->find($row['id']);
            if ($job === null)
                $job = new Job();

            $job->setId($row['id']);
            $this->copyI18NProperty($job, 'setName', $row, 'name');
            $this->dm->persist($job);

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
