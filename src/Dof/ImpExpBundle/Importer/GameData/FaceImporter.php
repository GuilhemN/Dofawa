<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\CharactersBundle\Entity\Face;
use Dof\GraphicsBundle\EntityLook;

class FaceImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'breed_faces';
    const BETA_DATA_SET = 'beta_breed_faces';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Head o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $breedRepo = $this->dm->getRepository('DofCharactersBundle:Breed');
        $repo = $this->dm->getRepository('DofCharactersBundle:Face');
        foreach ($all as $row) {
            $breed = $breedRepo->find($row['breed']);
            if ($breed === null || ($breed->isPreliminary() ^ $beta))
                continue;
            $face = $repo->find($row['skins']);
            if ($face === null) {
                $face = new Face();
                $face->setId($row['skins']);
            }
            $face->setBreed($breed);
            $face->setLabel($row['label']);
            $face->setOrder($row['order']);
            $face->setGender($row['gender']);
            $this->dm->persist($face);
        }
    }
}
