<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

class BreedDefaultColorsImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'breed_default_colors';
    const BETA_DATA_SET = 'beta_breed_default_colors';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $colors = [];
        $genders = ['male', 'female'];
        foreach ($genders as $gender) {
            $stmt = $conn->query('SELECT o.* FROM '.$db.'.D2O_Breed_'.$gender.'Color o');
            foreach ($stmt->fetchAll() as $row) {
                $id = intval($row['id']);
                $index = intval($row['_index1']);
                $value = intval($row['value']);
                if (!isset($colors[$id])) {
                    $colors[$id] = ['male' => [], 'female' => []];
                }
                $colors[$id][$gender][$index] = $value;
            }
            $stmt->closeCursor();
        }
        $repo = $this->dm->getRepository('DofCharacterBundle:Breed');
        foreach ($colors as $id => $row) {
            $breed = $repo->find($id);
            if ($breed === null || ($breed->isPreliminary() ^ $beta)) {
                continue;
            }
            $breed->setMaleDefaultColors($row['male']);
            $breed->setFemaleDefaultColors($row['female']);
            $this->dm->persist($breed);
        }
    }
}
