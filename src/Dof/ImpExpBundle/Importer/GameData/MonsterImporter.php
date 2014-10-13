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
            'MIN(g.movementPoints) as min_movement_points, MAX(g.movementPoints) as max_movement_points, ' .

            'MIN(g.earthResistance) as min_earth_resistance, MAX(g.earthResistance) as max_earth_resistance, ' .
            'MIN(g.airResistance) as min_air_resistance, MAX(g.airResistance) as max_air_resistance, ' .
            'MIN(g.fireResistance) as min_fire_resistance, MAX(g.fireResistance) as max_fire_resistance, ' .
            'MIN(g.waterResistance) as min_water_resistance, MAX(g.waterResistance) as max_water_resistance, ' .
            'MIN(g.neutralResistance) as min_neutral_resistance, MAX(g.neutralResistance) as max_neutral_resistance ' .
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
                $tpl->setMaxActionPoints($row['max_action_points']);
                $tpl->setMinMovementPoints($row['min_movement_points']);
                $tpl->setMaxMovementPoints($row['max_movement_points']);

                $tpl->setMinEarthResistance($row['min_earth_resistance']);
                $tpl->setMaxEarthResistance($row['max_earth_resistance']);
                $tpl->setMinAirResistance($row['min_air_resistance']);
                $tpl->setMaxAirResistance($row['max_air_resistance']);
                $tpl->setMinFireResistance($row['min_fire_resistance']);
                $tpl->setMaxFireResistance($row['max_fire_resistance']);
                $tpl->setMinWaterResistance($row['min_water_resistance']);
                $tpl->setMaxWaterResistance($row['max_water_resistance']);
                $tpl->setMinNeutralResistance($row['min_neutral_resistance']);
                $tpl->setMaxNeutralResistance($row['max_neutral_resistance']);

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
