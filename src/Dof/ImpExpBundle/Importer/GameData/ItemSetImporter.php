<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterInterface;

class ItemSetImporter implements ImporterInterface
{
    /**
     * @var object
     */
    private $conn;

    /**
     * @var ObjectManager
     */
    private $dm;

    public function __construct($conn, ObjectManager $dm)
    {
        $this->conn = $conn;
        $this->dm = $dm;
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {

    }
}
