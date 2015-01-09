<?php

namespace Dof\ImpExpBundle\Importer\GameData\Item;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterInterface;
use Dof\ImpExpBundle\ImporterFlags;

class ItemTemplateSkinImporter implements ImporterInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var ObjectManager
     */
    protected $dm;

    private $loaders;

    public function __construct($path, ObjectManager $dm)
    {
        $this->path = $path;
        $this->dm = $dm;
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        if ($dataSet != 'item_template_skins')
            return;
        $this->loaders[0]->setEnabled(false);
        $this->loaders[1]->setEnabled(false);

        $data = json_decode(file_get_contents($this->path), true);
        $this->dm->clear();
        $repo = $this->dm->getRepository('DofItemsBundle:SkinnedEquipmentTemplate');
        foreach ($data as $row) {
            $equip = $repo->find($row['id']);
            if ($equip !== null) {
                $equip->setSkin($row['skin']);
                $this->dm->persist($equip);
            }
        }
        if (($flags & ImporterFlags::DRY_RUN) == 0)
            $this->dm->flush();
        $this->dm->clear();

        $this->loaders[0]->setEnabled(true);
        $this->loaders[1]->setEnabled(true);
    }

    public function setLoaders($loaders){
        $this->loaders = $loaders;
    }
}
