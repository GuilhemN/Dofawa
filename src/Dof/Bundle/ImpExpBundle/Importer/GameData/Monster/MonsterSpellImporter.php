<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;

class MonsterSpellImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'monster_spells';
    const BETA_DATA_SET = 'beta_monster_spells';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Monster_spell o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $g = [];
        foreach($all as $row)
            $g[$row['id']][] = $row;

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $spellRepo = $this->dm->getRepository('DofCharacterBundle:Spell');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($g));
        foreach ($g as $monster => $spells) {
            $monster = $repo->find($monster);
            if($monster === null or $monster->isPreliminary() ^ $beta)
                continue;

                foreach($spells as $row){
                    $spell = $spellRepo->find($row['value']);
                    if($spell === null)
                        continue;

                    $monster->addSpell($spell);
                    $spell->addMonster($monster);
                }

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
