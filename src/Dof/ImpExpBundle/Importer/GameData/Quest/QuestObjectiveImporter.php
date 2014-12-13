<?php

namespace Dof\ImpExpBundle\Importer\GameData\Quest;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\QuestBundle\Entity\QuestObjective;

class QuestStepImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'quest_steps_objectives';
    const BETA_DATA_SET = 'beta_quest_steps_objectives';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_QuestObjective o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofQuestBundle:QuestObjective');
        $questSRepo = $this->dm->getRepository('DofQuestBundle:QuestStep');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $questStep = $questSRepo->find($row['stepId']);
            if($quest === null or $questStep->getQuest()->isPreliminary() ^ $beta)
                continue;
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new QuestObjective();
                $tpl->setId($row['id']);
            }
            $tpl->setStep($questStep);
            $tpl->setMapId($row['mapId']);
            $tpl->setX($row['coords_x']);
            $tpl->setY($row['coords_y']);

            $this->dm->persist($tpl);

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
