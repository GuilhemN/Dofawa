<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Spell;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\CharacterBundle\Entity\Spell;

class SpellImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'spells';
    const BETA_DATA_SET = 'beta_spells';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write) {
            $this->dm->createQuery('UPDATE DofCharacterBundle:Spell s SET s.deprecated = true')->execute();
        }

        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('description', $locales).
        $this->generateD2ISelects('name', $locales).
        ' FROM '.$db.'.D2O_Spell o'.
        $this->generateD2IJoins('name', $db, $locales).
        $this->generateD2IJoins('description', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $stmt = $conn->query('SELECT * FROM '.$db.'.D2O_Breed_breedSpellId');
        $breedSpells = $stmt->fetchAll();
        $stmt->closeCursor();

        $joins = array();
        foreach ($breedSpells as $join) {
            $joins[$join['value']][] = $join['id'];
        }

        $repo = $this->dm->getRepository('DofCharacterBundle:Spell');
        $breedRepo = $this->dm->getRepository('DofCharacterBundle:Breed');
        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($all));
        }
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new Spell();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
                $tpl->setPubliclyVisible(false);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease()) {
                    $tpl->setRelease($release);
                }
                $tpl->setPreliminary($beta);
                if (!empty($row['nameId'])) {
                    $this->copyI18NProperty($tpl, 'name', $row, 'name');
                }
                if (!empty($row['descriptionId'])) {
                    $this->copyI18NProperty($tpl, 'description', $row, 'description');
                }

                $tpl->setTypeId($row['typeId']);
                $tpl->setIconId($row['iconId']);

                $tpl->getBreeds()->clear();
                if (isset($joins[$row['id']])) {
                    foreach ($joins[$row['id']] as $breedId) {
                        $breed = $breedRepo->find($breedId);
                        if ($breed !== null) {
                            $tpl->addBreed($breed);
                        }
                    }
                }

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
