<?php

namespace Dof\ImpExpBundle\Importer\GameData\Map;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\MapBundle\Entity\MapPosition;

class PositionImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'map_positions';
    const BETA_DATA_SET = 'beta_map_positions';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofMapBundle:MapPosition s SET s.deprecated = true')->execute();
        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('name', $locales) .
        ' FROM ' . $db . '.D2O_MapPosition o' .
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofMapBundle:MapPosition');
        $subRepo = $this->dm->getRepository('DofMapBundle:SubArea');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $subArea = $subRepo->find($row['subAreaId']);
            if($subArea === null)
                continue;
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new MapPosition();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $tpl->setSubArea($subArea);
                $tpl->setX($row['posX']);
                $tpl->setY($row['posY']);
                $tpl->setOutdoor($row['outdoor']);
                $tpl->setCapabilities($row['capabilities']);
                $tpl->setWorldMap($row['worldMap']);
                $tpl->setHasPriorityOnWorldMap($row['hasPriorityOnWorldmap']);

                $this->copyI18NProperty($tpl, 'setName', $row, 'name');
                if($tpl->getNameFr() === null)
                    $tpl->setNameFr('map');
                $this->dm->persist($tpl);
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
