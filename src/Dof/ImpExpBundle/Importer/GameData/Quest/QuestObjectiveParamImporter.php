<?php

namespace Dof\ImpExpBundle\Importer\GameData\Quest;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

class QuestObjectiveParamImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'quest_step_objectives_params';
    const BETA_DATA_SET = 'beta_quest_step_objective_params';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_QuestObjective_parameter o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $params = [];
        foreach($all as $row)
            $params[$row['_index0']][$row['_index1']] = $row['value'];

        $repo = $this->dm->getRepository('DofQuestBundle:QuestObjective');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($params as $k => $p) {
            $objective = $repo->find($k);
            if($objective === null or $$objective->getQuestStep()->getQuest()->isPreliminary() ^ $beta)
                continue;

            foreach($p as $i => $v)
                $objective->setParam($v, $i);

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
