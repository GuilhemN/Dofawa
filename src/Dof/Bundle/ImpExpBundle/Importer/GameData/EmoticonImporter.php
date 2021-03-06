<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\CharacterBundle\Entity\Emoticon;

class EmoticonImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'emoticons';
    const BETA_DATA_SET = 'beta_emoticons';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        if (!$beta && $write) {
            $this->dm->createQuery('UPDATE DofCharacterBundle:Emoticon s SET s.deprecated = true')->execute();
        }

        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('name', $locales).
        $this->generateD2ISelects('shortcut', $locales).
        ' FROM '.$db.'.D2O_Emoticon o'.
        $this->generateD2IJoins('name', $db, $locales).
        $this->generateD2IJoins('shortcut', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofCharacterBundle:Emoticon');

        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($all));
        }

        foreach ($all as $row) {
            $emoticon = $repo->find($row['id']);
            if ($emoticon === null) {
                $emoticon = new Emoticon();
                $emoticon->setId($row['id']);
                $emoticon->setDeprecated(true);
            }
            if ($emoticon->isDeprecated()) {
                $emoticon->setDeprecated(false);
                if (!$emoticon->getRelease()) {
                    $emoticon->setRelease($release);
                }
                $emoticon->setPreliminary($beta);
                $emoticon->setOrder($row['order']);
                $emoticon->setAura($row['aura']);
                $this->copyI18NProperty($emoticon, 'name', $row, 'name');
                $this->copyI18NProperty($emoticon, 'shortcut', $row, 'shortcut');

                $this->dm->persist($emoticon);
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
