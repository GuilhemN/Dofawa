<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

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
              JOIN ' . $db . '.D2O_Recipe_ingredientId o2 on o2.resultId = o.resultId and o2._index1 = o._index1
              ORDER BY o.resultId');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofItemsBundle:ItemTemplate');

        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));

        foreach ($all as $row) {
            $cached = false;

            // Si item est identique au précédent
            if(!isset($item) || $item->getId() != $row['id'])
                // Récupération de l'item (composé)
                $item = $repo->findOneWithJoins(array('id' => $row['id']));
            else
                // Si récupérer depuis la précédente boucle
                $cached = true;

            if(!isset($ingredient) or $ingredient->getId() != $row['ingredient'])
                $ingredient = $repo->findOneById($row['ingredient']);

            // Passe la recette si l'item n'existe pas ou si item de la béta mais connexion en offi et inversement
            if ($item === null || ($item->isPreliminary() ^ $beta))
                continue;

            // Passe la recette si un ingrédient a été rentré manuellement
            foreach($item->getComponents() as $component)
                if($component->isSticky == true)
                    continue 2;

            // Si droit en écriture et 1er ingrédient
            if ($write && !$cached)
                // Suppression des recettes en bdd
                $this->dm->createQuery('DELETE DofItemsBundle:ItemComponent s  WHERE s.compound = ' . $item->getId())->execute() ;

            // Création de l'ingrédient
            $component = new ItemComponent();
            $component->setCompound($item);
            $component->setComponent($ingredient);
            $component->setQuantity($row['quantity']);
            $component->setSticky(false);

            $this->dm->persist($component);

            // Enregistrement régulier
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
