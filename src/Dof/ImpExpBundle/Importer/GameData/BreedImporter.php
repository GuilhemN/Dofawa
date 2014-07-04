<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\CharactersBundle\Entity\Breed;
use Dof\GraphicsBundle\EntityLook;

class BreedImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'breeds';
    const BETA_DATA_SET = 'beta_breeds';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofCharactersBundle:Breed b SET b.deprecated = true')->execute();
        $stmt = $conn->query('SELECT o.*' .
            $this->generateD2ISelects('shortName', $locales) .
            $this->generateD2ISelects('longName', $locales) .
            $this->generateD2ISelects('description', $locales) .
            $this->generateD2ISelects('gameplayDescription', $locales) .
            ' FROM ' . $db . '.D2O_Breed o' .
            $this->generateD2IJoins('shortName', $db, $locales) .
            $this->generateD2IJoins('longName', $db, $locales) .
            $this->generateD2IJoins('description', $db, $locales) .
            $this->generateD2IJoins('gameplayDescription', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofCharactersBundle:Breed');
        foreach ($all as $row) {
            $breed = $repo->find($row['id']);
            if ($breed === null) {
                $breed = new Breed();
                $breed->setDeprecated(true);
                $breed->setId($row['id']);
            }
            if ($breed->isDeprecated()) {
                $breed->setDeprecated(false);
                if (!$breed->getRelease())
                    $breed->setRelease($release);
                $breed->setPreliminary($beta);
                $this->copyI18NProperty($breed, 'setName', $row, 'shortName');
                $this->copyI18NProperty($breed, 'setLongName', $row, 'longName');
                $this->copyI18NProperty($breed, 'setDescription', $row, 'description');
                $this->copyI18NProperty($breed, 'setGameplayDescription', $row, 'gameplayDescription');
                $maleLook = new EntityLook($row['maleLook']);
                $breed->setMaleSkin($maleLook->getSkins()[0]);
                $breed->setMaleLodefSkin($breed->getMaleSkin());
                $breed->setMaleDefaultColors([ ]);
                $breed->setMaleSize($maleLook->getScaleY() * 100);
                $femaleLook = new EntityLook($row['femaleLook']);
                $breed->setFemaleSkin($femaleLook->getSkins()[0]);
                $breed->setFemaleLodefSkin($breed->getFemaleSkin());
                $breed->setFemaleDefaultColors([ ]);
                $breed->setFemaleSize($femaleLook->getScaleY() * 100);
                $breed->setMaleArtworkBone($row['maleArtwork']);
                $breed->setFemaleArtworkBone($row['femaleArtwork']);
                $breed->setCreatureBone($row['creatureBonesId']);
                $this->dm->persist($breed);
            }
        }
        if ($write)
            $this->dm->flush();
        $this->dm->clear();
    }
}
