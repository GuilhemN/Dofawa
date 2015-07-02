<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Quest;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\QuestBundle\Entity\QuestObjectiveTemplate;

class QuestObjectiveTemplateImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'quest_objective_templates';
    const BETA_DATA_SET = 'beta_quest_objective_templates';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write) {
            $this->dm->createQuery('UPDATE DofQuestBundle:QuestObjectiveTemplate s SET s.deprecated = true')->execute();
        }
        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('name', $locales).
            ' FROM '.$db.'.D2O_QuestObjectiveType o'.
            $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofQuestBundle:QuestObjectiveTemplate');
        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($all));
        }
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new QuestObjectiveType();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease()) {
                    $tpl->setRelease($release);
                }
                $tpl->setPreliminary($beta);

                $this->copyI18NProperty($tpl, 'name', $row, 'name');
                $this->dm->persist($tpl);
            }
            ++$rowsProcessed;
            if (($rowsProcessed % 300) == 0) {
                $this->dm->flush();
                $this->dm->clear();
                if ($output && $progress) {
                    $progress->advance(300);
                }
            }
        }
        if ($output && $progress) {
            $progress->finish();
        }
    }
}
