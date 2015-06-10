<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\CharacterBundle\Entity\AlignmentSide;

class AlignmentSideImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'alignment_sides';
    const BETA_DATA_SET = 'beta_alignment_sides';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write) {
            $this->dm->createQuery('UPDATE DofCharacterBundle:AlignmentSide b SET b.deprecated = true')->execute();
        }
        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('name', $locales).
        ' FROM '.$db.'.D2O_AlignmentSide o'.
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofCharacterBundle:AlignmentSide');
        foreach ($all as $row) {
            $side = $repo->find($row['id']);
            if ($side === null) {
                $side = new AlignmentSide();
                $side->setDeprecated(true);
                $side->setId($row['id']);
            }
            if ($side->isDeprecated()) {
                $side->setDeprecated(false);
                if (!$side->getRelease()) {
                    $side->setRelease($release);
                }
                $side->setPreliminary($beta);
                $this->copyI18NProperty($side, 'setName', $row, 'name');
                $side->setCanConquest($row['canConquest']);

                $this->dm->persist($side);
                $this->su->reassignSlug($side);
            }
        }
    }
}
