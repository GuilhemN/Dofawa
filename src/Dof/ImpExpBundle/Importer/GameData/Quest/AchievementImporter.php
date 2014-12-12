<?php

namespace Dof\ImpExpBundle\Importer\GameData\Quest;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\QuestBundle\Entity\Achievement;

class AchievementImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'achievements';
    const BETA_DATA_SET = 'beta_achievements';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
        $this->dm->createQuery('UPDATE DofQuestBundle:Achievement s SET s.deprecated = true')->execute();
        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('name', $locales) .
        $this->generateD2ISelects('description', $locales) .
        ' FROM ' . $db . '.D2O_Achievement o' .
        $this->generateD2IJoins('name', $db, $locales) .
        $this->generateD2IJoins('description', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofQuestBundle:Achievement');
        $categRepo = $this->dm->getRepository('DofQuestBundle:AchievementCategory');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $category = $categRepo->find($row['categoryId']);
            if($category === null)
            continue;
            $tpl = $repo->find($row['id']);
            $parent = $repo->find($row['parentId']);
            if ($tpl === null) {
                $tpl = new Achievement();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $tpl->setOrder($row['order']);
                $tpl->setIconId($row['iconId']);
                $tpl->setPoints($row['points']);
                $tpl->setLevel($row['level']);
                $tpl->setKamasRatio($row['kamasRatio']);
                $tpl->setXpRatio($row['experienceRatio']);
                $tpl->setCategory($category);

                $this->copyI18NProperty($tpl, 'setName', $row, 'name');
                $this->copyI18NProperty($tpl, 'setDescription', $row, 'description');
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
