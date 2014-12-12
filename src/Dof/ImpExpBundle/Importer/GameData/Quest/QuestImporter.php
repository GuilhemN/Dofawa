<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\QuestBundle\Entity\Quest;
use Dof\QuestBundle\QuestType;

class QuestImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'quests';
    const BETA_DATA_SET = 'beta_quests';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofQuestBundle:Quest s SET s.deprecated = true')->execute();
        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('name', $locales) .
        ' FROM ' . $db . '.D2O_Quest o' .
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofQuestBundle:Quest');
        $categRepo = $this->dm->getRepository('DofQuestBundle:QuestCategory');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $category = $categRepo->find($row['categoryId']);
            if($category === null)
                continue;
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new Quest();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $tpl->setIsRepeatable($row['isRepeatable']);
                $tpl->setIsDungeonQuest($row['isDungeonQuest']);
                $tpl->setCategory($category);
                $tpl->setLevelMin($row['levelMin']);
                $tpl->setLevelMax($row['levelMax']);

                if($row['repeatType'] == 3)
                    $tpl->setType(QuestType::ALMANAX);
                elseif($row['isRepeatable'])
                    $tpl->setType(QuestType::REPEATABLE);
                else
                    $tpl->setType(QuestType::ONE_SHOT);


                $this->copyI18NProperty($tpl, 'setName', $row, 'name');
                $this->dm->persist($tpl);
            }
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
