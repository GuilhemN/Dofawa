<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

class BreedDefaultColorsImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'breed_default_colors';
    const BETA_DATA_SET = 'beta_breed_default_colors';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        $colors = [ ];
        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Breed_maleColor o');
        foreach ($stmt->fetchAll() as $row) {
            $id = intval($row['id']);
            $index = intval($row['_index1']);
            $value = intval($row['value']);
            if (!isset($colors[$id]))
                $colors[$id] = [ 'male' => [ ], 'female' => [ ] ];
            $colors[$id]['male'][$index] = $value;
        }
        $stmt->closeCursor();
        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Breed_femaleColor o');
        foreach ($stmt->fetchAll() as $row) {
            $id = intval($row['id']);
            $index = intval($row['_index1']);
            $value = intval($row['value']);
            if (!isset($colors[$id]))
                $colors[$id] = [ 'male' => [ ], 'female' => [ ] ];
            $colors[$id]['female'][$index] = $value;
        }
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofCharactersBundle:Breed');
        foreach ($colors as $id => $row) {
            $breed = $repo->find($id);
            if ($breed === null || ($breed->isPreliminary() ^ $beta))
                continue;
            $breed->setMaleDefaultColors($row['male']);
            $breed->setFemaleDefaultColors($row['female']);
            $this->dm->persist($breed);
        }
        if ($write)
            $this->dm->flush();
        $this->dm->clear();
    }
}
