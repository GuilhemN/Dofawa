<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\MonsterBundle\Entity\MonsterRace;

class MonsterRaceImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'monster_races';
    const BETA_DATA_SET = 'beta_monster_races';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write) {
            $this->dm->createQuery('UPDATE DofMonsterBundle:MonsterRace s SET s.deprecated = true')->execute();
        }

        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('name', $locales).
        ' FROM '.$db.'.D2O_MonsterRace o'.
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofMonsterBundle:MonsterRace');
        $superRaceRepo = $this->dm->getRepository('DofMonsterBundle:MonsterSuperRace');
        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($all));
        }
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            $superRace = $superRaceRepo->find($row['superRaceId']);
            if ($superRace === null) {
                continue;
            }
            if ($tpl === null) {
                $tpl = new MonsterRace();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease()) {
                    $tpl->setRelease($release);
                }
                $tpl->setPreliminary($beta);
                $tpl->setParent($superRace);
                $this->copyI18NProperty($tpl, 'setName', $row, 'name');
                if ($tpl->getNameFr() === null) {
                    $tpl->setNameFr('Monstres');
                }

                $this->dm->persist($tpl);
                $this->su->reassignSlug($tpl);
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
