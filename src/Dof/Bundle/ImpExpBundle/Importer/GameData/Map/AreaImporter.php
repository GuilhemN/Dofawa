<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Map;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\MapBundle\Entity\Area;

class AreaImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'map_sub_areas';
    const BETA_DATA_SET = 'beta_map_sub_areas';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write) {
            $this->dm->createQuery('UPDATE DofMapBundle:Area s SET s.deprecated = true')->execute();
        }
        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('name', $locales).
        ' FROM '.$db.'.D2O_Area o'.
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofMapBundle:Area');
        $superRepo = $this->dm->getRepository('DofMapBundle:SuperArea');
        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($all));
        }
        foreach ($all as $row) {
            $superArea = $superRepo->find($row['superAreaId']);
            if ($superArea === null) {
                continue;
            }
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new Area();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease()) {
                    $tpl->setRelease($release);
                }
                $tpl->setPreliminary($beta);
                $tpl->setSuperArea($superArea);
                $tpl->setLeft($row['bounds_x']);
                $tpl->setTop($row['bounds_y']);
                $tpl->setWidth($row['bounds_width']);
                $tpl->setHeight($row['bounds_height']);

                $this->copyI18NProperty($tpl, 'name', $row, 'name');
                $this->dm->persist($tpl);
                
            }
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
