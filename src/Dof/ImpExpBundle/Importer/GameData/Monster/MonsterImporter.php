<?php

namespace Dof\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\MonsterBundle\Entity\Monster;

class MonsterImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'monsters';
    const BETA_DATA_SET = 'beta_monsters';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofMonsterBundle:Monster s SET s.deprecated = true')->execute();

        $stmt = $conn->query('SELECT o.*' .
            $this->generateD2ISelects('name', $locales) .
            ' FROM ' . $db . '.D2O_Monster o' .
            $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $raceRepo = $this->dm->getRepository('DofMonsterBundle:MonsterRace');
        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            $race = $raceRepo->find($row['race']);
            if($race === null)
                continue;
            if ($tpl === null) {
                $tpl = new Monster();
                $tpl->setDeprecated(true);
                $tpl->setVisible(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $tpl->setRace($race);
                $tpl->setLook($row['look']);
                $tpl->setUseSummonSlot($row['useSummonSlot']);
                $tpl->setUseBombSlot($row['useBombSlot']);
                $tpl->setCanPlay($row['canPlay']);
                $tpl->setCanTackle($row['canTackle']);
                $tpl->setBoss($row['isBoss']);
                $tpl->setMiniBoss($row['isMiniBoss']);
                $tpl->setCanBePushed($row['canBePushed']);
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
