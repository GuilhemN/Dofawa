<?php

namespace Dof\ImpExpBundle\Importer\GameData\Item;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\ImpExpBundle\ImporterFlags;

use Dof\ItemsBundle\Entity\ItemType;

class ItemTemplateImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'item_templates';
    const BETA_DATA_SET = 'beta_item_templates';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofItemsBundle:ItemTemplate s SET s.deprecated = true')->execute();
        $stmt = $conn->query('SELECT o.*' .
            $this->generateD2ISelects('name', $locales) .
            $this->generateD2ISelects('description', $locales) .
            ' FROM ' . $db . '.D2O_Item o' .
            $this->generateD2IJoins('name', $db, $locales) .
            $this->generateD2IJoins('description', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $typeRepo = $this->dm->getRepository('DofItemsBundle:ItemType');
        $setRepo = $this->dm->getRepository('DofItemsBundle:ItemSet');
        $repo = $this->dm->getRepository('DofItemsBundle:ItemTemplate');
        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $type = $typeRepo->find($row['typeId']);
                $tpl = $type->createItemTemplate();
                $tpl->setDeprecated(true);
                $tpl->setVisible(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $this->copyI18NProperty($tpl, 'setName', $row, 'name');
                $this->copyI18NProperty($tpl, 'setDescription', $row, 'description');
                $tpl->setCriteria(($row['criteria'] === 'null') ? null : $row['criteria']);
                $tpl->setLevel($row['level']);
                $tpl->setWeight($row['realWeight']);
                $tpl->setIconId($row['iconId']);
                $tpl->setTradeable($row['exchangeable'] == '1');
                $tpl->setNpcPrice($row['price']);
                if ($tpl->isEquipment()) {
                    $tpl->setEnhanceable($row['enhanceable'] == '1');
                    $set = ($row['itemSetId'] == '-1') ? null : $setRepo->find($row['itemSetId']);
                    if($row['typeId'] != 177)
                        $tpl->setSet($set);
                }
                if ($tpl->isWeapon()) {
                    $tpl->setTwoHanded($row['twoHanded'] == '1');
                    $tpl->setEthereal($row['etheral'] == '1');
                    $tpl->setCriticalHitBonus($row['criticalHitBonus']);
                    $tpl->setCriticalHitDenominator($row['criticalHitProbability']);
                    $tpl->setMaxCastsPerTurn($row['maxCastPerTurn']);
                    $tpl->setApCost($row['apCost']);
                    $tpl->setMinCastRange($row['minRange']);
                    $tpl->setMaxCastRange($row['range']);
                    $tpl->setSightCast($row['castTestLos'] == '1');
                    $tpl->setLineCast($row['castInLine'] == '1');
                    $tpl->setDiagonalCast($row['castInDiagonal'] == '1');
                }
                if ($tpl->isAnimal())
                    $tpl->setFavoriteAreaBonus($row['favoriteSubAreasBonus']);
                if ($tpl->isUseable()) {
                    $tpl->setUseableOnSelf($row['usable'] == '1');
                    $tpl->setUseableOnOthers($row['targetable'] == '1' && $row['nonUsableOnAnother'] != '1');
                    $tpl->setTargetable($row['targetable'] == '1');
                    $tpl->setTargetCriteria(($row['criteriaTarget'] === 'null') ? null : $row['criteriaTarget']);
                }
                $this->dm->persist($tpl);
                $this->su->reassignSlug($tpl);
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
