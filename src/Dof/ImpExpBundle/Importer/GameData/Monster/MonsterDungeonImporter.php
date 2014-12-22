<?php

namespace Dof\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

class MonsterDungeonImporter
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
        $raceRepo = $this->dm->getRepository('DofMonsterBundle:MonsterRace');
        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $dungeonRepo = $this->dm->getRepository('DofMonsterBundle:Dungeon');

        $boss = $repo->findByRace($raceRepo->findBySlug('boss'));
        foreach ($boss as $row){
            foreach($row->getSubAreas() as $subArea){
                $dungeon = $dungeonRepo->findOneByNameFr($subArea->getNameFr());

                if($dungeon !== null){
                    $row->setDungeon($dungeon);
                    continue 2;
                }
            }
            $row->setDungeon(null);
        }
        if (($flags & ImporterFlags::DRY_RUN) == 0)
            $this->dm->flush();
        $this->dm->clear();
    }
}
