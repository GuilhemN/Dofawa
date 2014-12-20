<?php

namespace Dof\ImpExpBundle\Importer\GameData\Monster;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

class MonsterSpellImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'monster_spells';
    const BETA_DATA_SET = 'beta_monster_spells';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofMonsterBundle:Monster s SET s.spells = null')->execute();

        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Monster_spell o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $g = [];
        foreach($all as $row)
            $g[$row['id']][] = $row;

        $repo = $this->dm->getRepository('DofMonsterBundle:Monster');
        $spellRepo = $this->dm->getRepository('DofCharactersBundle:Spell');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($g as $monster => $spells) {
            $monster = $repo->find($monster);
            if($monster === null or $monster->isPreliminary() ^ $beta)
                continue;

                foreach($spells as $spell){
                    $spell = $spellRepo->find($spell);
                    if($spell === null)
                        continue;

                    $monster->addSpell($spell);
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
