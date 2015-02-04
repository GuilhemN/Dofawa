<?php

namespace Dof\Bundle\ImpExpBundle;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

interface ImporterInterface
{
    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null);
}
