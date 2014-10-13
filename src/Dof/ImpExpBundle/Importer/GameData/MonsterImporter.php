<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

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

        $stmt = $conn->query('SELECT o.*, ' .
            'MIN(g.level) as min_level, MAX(g.level) as max_level, ' .
            'MIN(g.lifePoints) as min_life_points, MAX(g.lifePoints) as max_life_points, ' .
            'MIN(g.actionPoints) as min_action_points, MAX(g.actionPoints) as max_action_points, ' .
            'MIN(g.movementPoints) as min_movement_points, MAX(g.movementPoints) as max_movement_points ' .
            $this->generateD2ISelects('name', $locales) .
            ' FROM ' . $db . '.D2O_Monster o' .
            ' JOIN ' . $db . '.D2O_Monster_grade g on g.monsterId = o.id' .
            $this->generateD2IJoins('name', $db, $locales) .
            ' GROUP BY o.id');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
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
                $this->copyI18NProperty($tpl, 'setName', $row, 'name');

                $tpl->setMinLevel($row['min_level']);
                $tpl->setMaxLevel($row['max_level']);

                $tpl->setMinLifePoints($row['min_life_points']);
                $tpl->setMaxLifePoints($row['max_life_points']);

                $tpl->setMinActionPoints($row['min_action_points']);
                $tpl->setMaxActionPoints($row['min_action_points']);
                $tpl->setMinMouvementPoints($row['max_movement_points']);
                $tpl->setMaxMouvementPoints($row['max_movement_points']);

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
