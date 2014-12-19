<?php

namespace Dof\ImpExpBundle\Importer\GameData\Map;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\MapBundle\Area;

class AreaImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'areas';
    const BETA_DATA_SET = 'beta_areas';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofMapBundle:Area s SET s.deprecated = true')->execute();
        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('name', $locales) .
        ' FROM ' . $db . '.D2O_Area o' .
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofMapBundle:Area');
        $superRepo = $this->dm->getRepository('DofMapBundle:SuperArea');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $superArea = $superRepo->find($row['superAreaId']);
            if($category === null)
                continue;
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new Area();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $tpl->setSuperArea($superArea);
                $tpl->setLeft($row['bounds_x']);
                $tpl->setTop($row['bounds_y']);
                $tpl->setWidth($row['bounds_width']);
                $tpl->setHeight($row['bounds_height']);

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
