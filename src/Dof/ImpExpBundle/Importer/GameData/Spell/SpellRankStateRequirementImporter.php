<?php

namespace Dof\ImpExpBundle\Importer\GameData\Spell;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;
use XN\Persistence\CollectionSynchronizationHelper;

use Dof\CharactersBundle\Entity\SpellRankStateRequirement;

class SpellRankStateRequirementImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'spell_rank_states';
    const BETA_DATA_SET = 'beta_spell_rank_states';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_SpellLevel_stateForbidden o');
        $fStates = $stmt->fetchAll();
        $stmt->closeCursor();

        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_SpellLevel_stateRequired o');
        $rStates = $stmt->fetchAll();
        $stmt->closeCursor();

        $states = array();
        foreach($fStates as $row)
            $rankStates[$row['id']][] = $row + ['requirement' => false];
        foreach($rStates as $row)
            $rankStates[$row['id']][] = $row + ['requirement' => true];

        $repo = $this->dm->getRepository('DofCharactersBundle:SpellRankStateRequirement');
        $rankRepo = $this->dm->getRepository('DofCharactersBundle:SpellRank');
        $stateRepo = $this->dm->getRepository('DofCharactersBundle:State');

        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($rankStates));

        foreach ($rankStates as $rank => $states) {
            $rank = $rankRepo->find($rank);
            if($rank === null || ($rank->getSpell()->isPreliminary() ^ $beta))
                continue;

            CollectionSynchronizationHelper::synchronize($this->dm, $rank->getStateRequirements()->toArray(), $states, function () use ($rank) {
                $stateR = new SpellRankStateRequirement();
                $stateR->setSpellRank($rank);
                return $stateR;
            }, function ($stateR, $row) use($stateRepo){
                $state = $stateRepo->find($row['value']);
                $stateR->setState($state);
                $stateR->setRequirement($row['requirement']);
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
