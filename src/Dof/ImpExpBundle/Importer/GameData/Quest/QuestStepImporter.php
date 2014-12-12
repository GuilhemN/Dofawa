<?php

namespace Dof\ImpExpBundle\Importer\GameData\Quest;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\QuestBundle\Entity\QuestStep;

class QuestStepImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'quest_steps';
    const BETA_DATA_SET = 'beta_quest_steps';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('name', $locales) .
        $this->generateD2ISelects('description', $locales) .
        ' FROM ' . $db . '.D2O_QuestStep o' .
        $this->generateD2IJoins('name', $db, $locales) .
        $this->generateD2IJoins('description', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofQuestBundle:QuestStep');
        $questRepo = $this->dm->getRepository('DofQuestBundle:Quest');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $quest = $questRepo->find($row['questId']);
            if($quest === null or $quest->isPreliminary() ^ $beta)
                continue;
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new QuestStep();
                $tpl->setId($row['id']);
            }
            $tpl->setQuest($quest);
            $tpl->setDuration($row['duration'] * 100);
            $tpl->setKamasRatio($row['kamasRatio'] * 100);
            $tpl->setXpRatio($row['xpRatio'] * 100);
            $tpl->setOptimalLevel($row['optimalLevel']);

            $this->copyI18NProperty($tpl, 'setName', $row, 'name');
            $this->copyI18NProperty($tpl, 'setDescription', $row, 'description');
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
