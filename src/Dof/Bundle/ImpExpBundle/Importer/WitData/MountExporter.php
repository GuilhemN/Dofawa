<?php

namespace Dof\Bundle\ImpExpBundle\Importer\WitData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\ImpExpBundle\ImporterFlags;

class MountExporter extends AbstractWitDataExporter
{
    protected function doImport(OutputInterface $output = null)
    {
        $data = [
            'values' => []
        ];
        $mounts = $this->dm->getRepository('DofItemBundle:MountTemplate')->findNames();

        $rowsProcessed = 0;
        foreach($mounts as $mount){
            $this->callPut('entities/dragoturkey/values', ['value' => $mount['nameFr']]);
        }

    }
}
