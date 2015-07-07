<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Monster;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\ImporterInterface;
use Dof\Bundle\ImpExpBundle\ImporterFlags;

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
        if ($dataSet != 'monster_dungeons') {
            return;
        }
        $this->dm->clear();
        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $dungeonRepo = $this->dm->getRepository('DofMonsterBundle:Dungeon');

        $boss = $repo->findByBoss(true);

        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($boss));
        }

        foreach ($boss as $row) {
            foreach ($row->getSubAreas() as $subArea) {
                $dungeon = $dungeonRepo->findOneByName($subArea->getName());

                if ($dungeon !== null && !$row->getDungeons()->contains($dungeon)) {
                    $row->addDungeon($dungeon);
                }
            }

            ++$rowsProcessed;
            if (($rowsProcessed % 150) == 0) {
                if ($output && $progress) {
                    $progress->advance(150);
                }
            }
        }

        if (($flags & ImporterFlags::DRY_RUN) == 0) {
            $this->dm->flush();
        }
        $this->dm->clear();

        if ($output && $progress) {
            $progress->finish();
        }
    }

    private function progress(&$rowsProcessed, OutputInterface $output = null, ProgressHelper $progress = null)
    {
    }
}
