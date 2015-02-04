<?php

namespace Dof\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;
use XN\Persistence\CollectionSynchronizationHelper;

use Dof\MonsterBundle\Entity\MonsterDrop;

class MonsterDropImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'monster_drops';
    const BETA_DATA_SET = 'beta_monster_drops';

    private $loader;

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $this->loader->setEnabled(false);
        
        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Monster_drop o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $g = [];
        foreach($all as $row)
            $g[$row['monsterId']][] = $row;

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $itemRepo = $this->dm->getRepository('DofItemBundle:ItemTemplate');

        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($g));

        foreach ($g as $monster => $drops) {
            $monster = $repo->find($monster);
            if($monster === null || ($monster->isPreliminary() ^ $beta))
                continue;

            CollectionSynchronizationHelper::synchronize($this->dm, $monster->getDrops()->toArray(), $drops, function () use ($monster) {
                $monsterD = new MonsterDrop();
                $monsterD->setMonster($monster);
                return $monsterD;
            }, function ($monsterD, $row) use ($itemRepo) {
                $item = $itemRepo->find($row['objectId']);
                $monsterD->setObject($item);
                $monsterD->setIndex($row['_index1']);
                $monsterD->setMinPercent($row['percentDropForGrade1']);
                $monsterD->setMaxPercent($row['percentDropForGrade5']);
                $monsterD->setCount($row['count']);
                $monsterD->setThreshold($row['findCeil']);
                $monsterD->setHasCriteria($row['hasCriteria']);
            });

            ++$rowsProcessed;
            if (($rowsProcessed % 150) == 0) {
                if ($output && $progress)
                $progress->advance(150);
            }
        }
        if ($output && $progress)
            $progress->finish();
        $this->loader->setEnabled(true);
    }

    public function setLoader($loader) {
        $this->loader = $loader;
    }
}
