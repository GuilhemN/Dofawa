<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

class BreedLodefSkinsImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'breed_lodef_skins';
    const BETA_DATA_SET = 'beta_breed_lodef_skins';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $mappings = [];
        $stmt = $conn->query('SELECT o.* FROM '.$db.'.D2O_SkinMapping o');
        foreach ($stmt->fetchAll() as $row) {
            $id = intval($row['id']);
            $value = intval($row['lowDefId']);
            if ($id == $value) {
                unset($mappings[$id]);
            } else {
                $mappings[$id] = $value;
            }
        }
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofCharacterBundle:Breed');
        foreach ($repo->findBy(['preliminary' => $beta]) as $breed) {
            if (isset($mappings[$breed->getMaleSkin()]) || isset($mappings[$breed->getFemaleSkin()])) {
                if (isset($mappings[$breed->getMaleSkin()])) {
                    $breed->setMaleLodefSkin($mappings[$breed->getMaleSkin()]);
                }
                if (isset($mappings[$breed->getFemaleSkin()])) {
                    $breed->setFemaleLodefSkin($mappings[$breed->getFemaleSkin()]);
                }
                $this->dm->persist($breed);
            }
        }
    }
}
