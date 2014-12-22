<?php

namespace Dof\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\MonsterBundle\Entity\Dungeon;

class DungeonImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'dungeons';
    const BETA_DATA_SET = 'beta_dungeons';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofMonsterBundle:Dungeon s SET s.deprecated = true')->execute();

        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('name', $locales) .
        ' FROM ' . $db . '.D2O_Dungeon o' .
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofMonsterBundle:Dungeon');
        $mapRepo = $this->dm->getRepository('DofMapBundle:MapPosition');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            $entrance = $mapRepo->find($row['entranceMapId']);
            $exit = $mapRepo->find($row['exitMapId']);
            if($entrance === null)
                continue;
            if ($tpl === null) {
                $tpl = new Dungeon();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $tpl->setEntranceMap($entrance);
                $tpl->setExitMap($exit);
                $tpl->setOptimalPlayerLevel($row['optimalPlayerLevel']);
                $this->copyI18NProperty($tpl, 'setName', $row, 'name');

                $this->dm->persist($tpl);
                $this->su->reassignSlug($tpl);
            }
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
