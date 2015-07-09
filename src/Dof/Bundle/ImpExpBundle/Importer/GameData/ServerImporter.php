<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\MainBundle\Entity\Server;

class ServerImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'servers';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('name', $locales).
        $this->generateD2ISelects('comment', $locales).
        ' FROM '.$db.'.D2O_Server o'.
        $this->generateD2IJoins('name', $db, $locales));
        $this->generateD2IJoins('comment', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofMainBundle:Server');

        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($all));
        }

        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new Server();
                $tpl->setId($row['id']);
            }

            $this->copyI18NProperty($tpl, 'name', $row, 'name');
            $this->copyI18NProperty($tpl, 'description', $row, 'comment');

            $tpl->setLocale($row['language']);
            $tpl->setGameType($row['gameTypeId']);

            $this->dm->persist($tpl);

            ++$rowsProcessed;
            if (($rowsProcessed % 300) == 0) {
                $this->dm->flush();
                $this->dm->clear();
                if ($output && $progress) {
                    $progress->advance(300);
                }
            }
        }
        if ($output && $progress) {
            $progress->finish();
        }
    }
}
