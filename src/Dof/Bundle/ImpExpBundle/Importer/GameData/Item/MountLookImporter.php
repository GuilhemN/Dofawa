<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Item;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\GraphicsBundle\EntityLook;
use Dof\Bundle\ItemBundle\AnimalColorizationType;

class MountLookImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'mount_looks';
    const BETA_DATA_SET = 'beta_mount_looks';

    private $loaders;

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $this->loaders[0]->setEnabled(false);
        $this->loaders[1]->setEnabled(false);

        $stmt = $conn->query('SELECT o.*'.
        $this->generateD2ISelects('name', $locales).
        ' FROM '.$db.'.D2O_Mount o'.
        $this->generateD2IJoins('name', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();
        $repo = $this->dm->getRepository('DofItemBundle:MountTemplate');
        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($all));
        }
        foreach ($all as $row) {
            $tpl = $repo->findOneByName($row['nameFr']);
            if ($tpl === null) {
                continue;
            }

            $look = new EntityLook($row['look']);
            $tpl->setBone($look->getBone());
            $tpl->setColors($look->getColors());
            $tpl->setSkins($look->getSkins());
            $tpl->setColorizationType(AnimalColorizationType::COLORS);
            $tpl->setSize($look->getScaleX() * 100);

            ++$rowsProcessed;
            if (($rowsProcessed % 300) == 0) {
                $this->dm->flush();
                $this->dm->clear();
                if ($output && $progress) {
                    $progress->advance(300);
                }
            }
        }
        if ($output && $progress) {
            $progress->finish();
        }

        $this->loaders[0]->setEnabled(true);
        $this->loaders[1]->setEnabled(true);
    }

    public function setLoaders($loaders)
    {
        $this->loaders = $loaders;
    }
}
