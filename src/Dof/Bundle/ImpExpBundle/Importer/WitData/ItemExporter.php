<?php

namespace Dof\Bundle\ImpExpBundle\Importer\WitData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\ImpExpBundle\ImporterFlags;

class ItemExporter extends AbstractWitDataExporter
{
    protected function doImport(OutputInterface $output = null)
    {
        $data = [
            'values' => []
        ];
        $items = $this->dm->getRepository('DofItemBundle:EquipmentTemplate')->findNames();
        $i = 0;
        foreach($items as $item){
            $i++;
            $data['values'][] = [
                'value' => $item['nameFr'],
                'expressions' => [
                    $item['nameFr'],
                    $item['nameEn'],
                    $item['nameDe'],
                    $item['nameEs'],
                    $item['nameIt'],
                    $item['nameJa'],
                    $item['namePt'],
                    $item['nameRu']
                ],
                'metadata' => json_encode(['id' => $item['id']])
            ];
            if($i % 200 == 0){
                $this->callPut('entities/item', $data);
                $data['values'] = '';
            }
        }
    }
}
