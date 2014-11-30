<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\CharactersBundle\Entity\Title;

class TitleImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'titles';
    const BETA_DATA_SET = 'beta_titles';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofCharactersBundle:Title s SET s.deprecated = true')->execute();

        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('nameMale', $locales) .
        $this->generateD2ISelects('nameFemale', $locales) .
        ' FROM ' . $db . '.D2O_Title o' .
        $this->generateD2IJoins('nameMale', $db, $locales) .
        $this->generateD2IJoins('nameFemale', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofCharactersBundle:Title');

        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));

        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            if ($tpl === null){
                $tpl = new Title();
                $tpl->setId($row['id']);
                $tpl->setDeprecated(true);
            }
            if($tpl->isDeprecated()){
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $tpl->setVisible($row['visible']);
                $tpl->setCategoryId($row['categoryId']);
                $this->copyI18NProperty($tpl, 'setNameMale', $row, 'nameMale');
                $this->copyI18NProperty($tpl, 'setNameFemale', $row, 'nameFemale');

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
