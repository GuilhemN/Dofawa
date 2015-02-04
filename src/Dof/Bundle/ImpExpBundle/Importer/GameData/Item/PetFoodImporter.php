<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Item;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;

use Dof\Bundle\ItemBundle\Entity\Job;

class PetFoodImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'pet_food';
    const BETA_DATA_SET = 'beta_pet_food';

    private $loaders;
    
    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $this->loaders[0]->setEnabled(false);
        $this->loaders[1]->setEnabled(false);

        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt1 = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Pet_foodItem o');
        $stmt2 = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Pet_foodType o');

        $foodItems = $stmt1->fetchAll();
        $foodTypes = $stmt2->fetchAll();

        foreach($foodItems as $row)
            $pets[$row['id']]['foodItems'][] = $row['value'];
        foreach($foodTypes as $row)
            $pets[$row['id']]['foodTypes'][] = $row['value'];

        $stmt1->closeCursor();
        $stmt2->closeCursor();

        $repo = $this->dm->getRepository('DofItemBundle:PetTemplate');
        $typeRepo = $this->dm->getRepository('DofItemBundle:ItemType');
        $itemRepo = $this->dm->getRepository('DofItemBundle:ItemTemplate');

        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($pets));

        foreach ($pets as $id => $v) {
            $pet = $repo->find($id);
            if ($pet === null || ($pet->isPreliminary() ^ $beta))
                continue;

            foreach($pet->getFoodTypes() as $foodType)
                $pet->removeFoodType($foodType);
            foreach($pet->getFoodItems() as $foodItem)
                $pet->removeFoodItem($foodItem);

            if(isset($v['foodTypes'])){
                $foodTypes = $typeRepo->findById($v['foodTypes']);
                foreach($foodTypes as $foodType)
                    $pet->addFoodType($foodType);
            }

            if(isset($v['foodItems'])){
                $foodItems = $itemRepo->findById($v['foodItems']);
                foreach($foodItems as $foodItem)
                    $pet->addFoodItem($foodItem);
            }

            ++$rowsProcessed;
            if (($rowsProcessed % 75) == 0) {
                $this->dm->flush();
                $this->dm->clear();
                if ($output && $progress)
                    $progress->advance(75);
            }
        }
        if ($output && $progress)
            $progress->finish();
        $this->loaders[0]->setEnabled(true);
        $this->loaders[1]->setEnabled(true);
    }

    public function setLoaders($loaders){
        $this->loaders = $loaders;
    }
}
