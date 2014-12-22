<?php

namespace Dof\ImpExpBundle\Importer\GameData\Monster;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterInterface;
use Dof\ImpExpBundle\ImporterFlags;

class MonsterDungeonImporter implements ImporterInterface
{
    /**
    * @var ObjectManager
    */
    protected $dm;

    public function __construct(ObjectManager $dm)
    {
        $this->dm = $dm;
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        if ($dataSet != 'monster_dungeons')
            return;
        $this->dm->clear();
        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $dungeonRepo = $this->dm->getRepository('DofMonsterBundle:Dungeon');

        $boss = $repo->findByBoss(true);

        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($boss));

        foreach ($boss as $row){
            foreach($row->getSubAreas() as $subArea){
                $dungeon = $dungeonRepo->findOneByNameFr($subArea->getNameFr());

                if($dungeon !== null){
                    $row->setDungeon($dungeon);
                    $this->progress($rowsProcessed, $output, $progress);
                    continue 2;
                }
            }
            $row->setDungeon(null);
            $this->progress($rowsProcessed, $output, $progress);
        }

        if (($flags & ImporterFlags::DRY_RUN) == 0)
            $this->dm->flush();
        $this->dm->clear();

        if ($output && $progress)
            $progress->finish();
    }

    private function progress(&$rowsProcessed, OutputInterface $output = null, ProgressHelper $progress = null) {
        ++$rowsProcessed;
        if (($rowsProcessed % 150) == 0) {
            if ($output && $progress)
                $progress->advance(150);
        }
    }
}
