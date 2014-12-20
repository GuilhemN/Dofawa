<?php

namespace Dof\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

class ArchMonsterImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'archi_monsters';
    const BETA_DATA_SET = 'beta_archi_monsters';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_MonsterMiniBos o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['monsterReplacingId']);
            $archi = $repo->find($row['id']);
            if($tpl === null or $archi === null or $tpl->isPreliminary() ^ $beta)
                continue;

            $tpl->setArchMonster($archi);
            $archi->setNormalMonster($tpl);

            ++$rowsProcessed;
            if (($rowsProcessed % 150) == 0) {
                $this->dm->flush();
                $this->dm->clear();
                if ($output && $progress)
                    $progress->advance(150);
            }
        }
        if ($output && $progress)
            $progress->finish();

    }
}
