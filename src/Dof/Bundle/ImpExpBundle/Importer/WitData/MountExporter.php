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
        foreach($mounts as $mount)
            $data['values'][] = [
                'value' => $mount['nameFr'],
                'expressions' => [
                    $mount['nameFr'],
                    $mount['nameEn'],
                    $mount['nameDe'],
                    $mount['nameEs'],
                    $mount['nameIt'],
                    $mount['nameJa'],
                    $mount['namePt'],
                    $mount['nameRu']
                ],
                'metadata' => json_encode(['id' => $mount['id']])
            ];

        $this->callPut('entities/mount', $data);
    }
}
