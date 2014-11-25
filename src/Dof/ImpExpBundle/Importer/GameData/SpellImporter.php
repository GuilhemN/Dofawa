<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\CharactersBundle\Entity\Spell;

class SpellImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'spells';
    const BETA_DATA_SET = 'beta_spells';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofCharactersBundle:Spell s SET s.deprecated = true')->execute();

        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('description', $locales) .
        $this->generateD2ISelects('name', $locales) .
        ' FROM ' . $db . '.D2O_Spell o' .
        $this->generateD2IJoins('name', $db, $locales) .
        $this->generateD2IJoins('description', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $stmt = $conn->query('SELECT * FROM ' . $db . '.D2O_Breed_breedSpellId');
        $breedSpells = $stmt->fetchAll();
        $stmt->closeCursor();

        $joins = array();
        foreach($breedSpells as $join)
            $joins[$join['value']][] = $join['id'];

        $repo = $this->dm->getRepository('DofCharactersBundle:Spell');
        $breedRepo = $this->dm->getRepository('DofCharactersBundle:Breed');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new Spell();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
                $tpl->setPubliclyVisible(false);

                $tpl->setNameFr('Sort sans nom');
                $tpl->setDescriptionFr('-');
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                if(!empty($row['nameId']))
                    $this->copyI18NProperty($tpl, 'setName', $row, 'name');
                if(!empty($row['descriptionId']))
                    $this->copyI18NProperty($tpl, 'setDescription', $row, 'description');

                $tpl->setTypeId($row['typeId']);
                $tpl->setIconId($row['iconId']);

                $tpl->getBreeds()->clear();
                if(isset($joins[$row['id']]))
                    foreach($joins[$row['id']] as $breedId){
                        $breed = $breedRepo->find($breedId);
                        if($breed !== null)
                            $tpl->addBreed($breed);
                    }

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
