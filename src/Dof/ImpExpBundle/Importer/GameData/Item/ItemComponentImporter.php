<?php

namespace Dof\ImpExpBundle\Importer\GameData\Item;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\ItemsBundle\Entity\ItemComponent;

class ItemComponentImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_components';
    const BETA_DATA_SET = 'beta_item_components';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        // Si bdd accessible en écriture
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $stmt = $conn->query('SELECT o.resultId as id, o.value as ingredient, o2.value as quantity
              FROM ' . $db . '.D2O_Recipe_ingredientId o
              JOIN ' . $db . '.D2O_Recipe_quantity o2 on o2.resultId = o.resultId and o2._index1 = o._index1
              ORDER BY o.resultId');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofItemsBundle:ItemTemplate');

        $items = array();
        foreach($all as $row){
            $items[$row['id']][ ] = array(
                'ingredient' => $row['ingredient'],
                'quantity' => $row['quantity']
            );
        }

        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($items));

        foreach ($items as $id => $v) {
            $item = $repo->find($id);

            if ($item === null || ($item->isPreliminary() ^ $beta))
                continue;

            foreach($item->getComponents() as $component)
                if($component->isSticky())
                    continue 2;

            // Suppression de l'ancienne recette
            if ($write)
                foreach($item->getComponents() as $component){
                    $item->removeComponent($component);
                    $this->dm->remove($component);
                }

            foreach($v as $recipeRow){
                $ingredient = $repo->find($recipeRow['ingredient']);

                if($ingredient === null)
                    continue;

                $component = new ItemComponent();
                $component->setCompound($item);
                $component->setComponent($ingredient);
                $component->setQuantity($recipeRow['quantity']);
                $component->setSticky(false);

                $this->dm->persist($component);
            }

            // Enregistrement régulier
            ++$rowsProcessed;
            if (($rowsProcessed % 150) == 0) {
                // Persistage en bdd + nettoyage
                $this->dm->flush();
                $this->dm->clear();

                // Avance de la barre de progression
                if ($output && $progress)
                    $progress->advance(150);
            }
        }

        if ($output && $progress)
            $progress->finish();
    }
}
