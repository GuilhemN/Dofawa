<?php

namespace Dof\Bundle\ImpExpBundle\Command;

use Dof\Bundle\ImpExpBundle\ImportManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    private $importManager;

    public function __construct(ImportManager $importManager)
    {
        $this->importManager = $importManager;
    }

    protected function configure()
    {
        $this
            ->setName('dof:imp-exp:list')
            ->setDescription('Lists outer sources from which data can be imported')
            ->addOption('requirements', 'r', InputOption::VALUE_REQUIRED, 'Only show requirements of a given source');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $verbosity = $output->getVerbosity();
        $requirements = $input->getOption('requirements');
        if (empty($requirements)) {
            $requirements = null;
        }
        $dataSetNames = ($requirements !== null) ? $this->importManager->getDataSetRequirements($requirements) : $this->importManager->getDataSets();
        $dataSets = [];
        foreach ($dataSetNames as $dataSet) {
            $dataSets[$dataSet] = $this->importManager->getDataSetImporter($dataSet);
        }
        if ($verbosity <= OutputInterface::VERBOSITY_QUIET) {
            return;
        }
        $output->writeln(($requirements !== null) ? ('Data sets required by <comment>'.$requirements.'</comment> :') : 'Available data sets :');
        foreach ($dataSets as $dataSet => $importer) {
            if ($verbosity >= OutputInterface::VERBOSITY_VERBOSE) {
                $clazz = get_class($this->importManager->getDataSetImporter($dataSet));
                if ($verbosity < OutputInterface::VERBOSITY_VERY_VERBOSE) {
                    $pos = strrpos($clazz, '\\');
                    if ($pos !== false) {
                        $clazz = substr($clazz, $pos + 1);
                    }
                }
                $output->writeln('- <comment>'.$dataSet.'</comment> (from '.$clazz.')');
            } else {
                $output->writeln('- <comment>'.$dataSet.'</comment>');
            }
        }
    }
}
