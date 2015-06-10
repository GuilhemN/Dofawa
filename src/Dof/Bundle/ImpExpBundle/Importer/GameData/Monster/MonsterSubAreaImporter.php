<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;

class MonsterSubAreaImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'monster_sub_areas';
    const BETA_DATA_SET = 'beta_monster_sub_areas';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt = $conn->query('SELECT o.* FROM '.$db.'.D2O_Monster_subarea o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $g = [];
        foreach ($all as $row) {
            $g[$row['id']][] = $row['value'];
        }

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $subAreaRepo = $this->dm->getRepository('DofMapBundle:SubArea');

        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($g));
        }

        foreach ($g as $monster => $areas) {
            $monster = $repo->find($monster);
            if ($monster === null || ($monster->isPreliminary() ^ $beta)) {
                continue;
            }

            foreach ($monster->getSubAreas() as $area) {
                $monster->removeSubArea($area);
                $area->removeMonster($monster);
            }

            $subAreas = $subAreaRepo->findById($areas);
            foreach ($subAreas as $area) {
                $monster->addSubArea($area);
                $area->addMonster($monster);
            }

            ++$rowsProcessed;
            if (($rowsProcessed % 300) == 0) {
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
