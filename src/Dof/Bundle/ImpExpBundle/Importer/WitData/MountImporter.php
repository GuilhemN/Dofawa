<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\ImpExpBundle\ImporterFlags;

class MountImporter extends AbstractGameDataImporter
{
    protected function doImport(OutputInterface $output = null)
    {
        $data = [
            'values' => []
        ];
        $mounts = $this->dm->getRepository('DofItemBundle:MountTemplate')->findNames();

        foreach($mounts as $mount)
            $data['values'] = [
                'value' => $mount['nameFr'],
                'expression' => $mount['nameEn'],
                'metadata' => [
                    'id' => $mount['id']
                    ]
            ];

        $this->callPut('entities/dragoturkey', $data);
    }
}
