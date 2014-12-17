<?php

namespace Dof\ImpExpBundle\Importer\GameData\Spell;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;
use XN\Persistence\CollectionSynchronizationHelper;

use Dof\MonsterBundle\Entity\MonsterGrade;

class MonsterGradeImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'monster_grades';
    const BETA_DATA_SET = 'beta_monster_grades';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Monster_grade o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $g = [];
        foreach($all as $row)
            $g[$row['monsterId']][] = $row;

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');

        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));

        foreach ($g as $monster => $grades) {
            $monster = $repo->find($monster);
            if($monster === null || ($monster->isPreliminary() ^ $beta))
                continue;

            CollectionSynchronizationHelper::synchronize($this->dm, $monster->getGrades()->toArray(), $grades, function () use ($monster) {
                $monsterG = new MonsterGrade();
                $monsterG->setMonster($monster);
                return $monsterG;
            }, function ($monsterG, $row) {
                $monsterG->setGrade($row['grade']);
                $monsterG->setLevel($row['level']);
                $monsterG->setLifePoints($row['lifePoints']);
                $monsterG->setActionPoints($row['actionPoints']);
                $monsterG->setMovementPoints($row['movementPoints']);
                $monsterG->setPaDodge($row['paDodge']);
                $monsterG->setPmDodge($row['pmDodge']);
                $monsterG->setWisdom($row['wisdom']);
                $monsterG->setEarthResistance($row['earthResistance']);
                $monsterG->setAirResistance($row['airResistance']);
                $monsterG->setFireResistance($row['fireResistance']);
                $monsterG->setWaterResistance($row['waterResistance']);
                $monsterG->setNeutralResistance($row['neutralResistance']);
                $monsterG->setGradeXp($row['gradeXp']);
            });

            ++$rowsProcessed;
            if (($rowsProcessed % 150) == 0) {
                if ($output && $progress)
                $progress->advance(150);
            }
        }
        if ($output && $progress)
        $progress->finish();
    }
}
