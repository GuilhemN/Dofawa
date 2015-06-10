<?php

namespace Dof\Bundle\ImpExpBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dof:imp-exp:list')
            ->setDescription('Lists outer sources from which data can be imported')
            ->addOption('requirements', 'r', InputOption::VALUE_REQUIRED, 'Only show requirements of a given source');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $impMgr = $this->getContainer()->get('dof_imp_exp.import_manager');
        $verbosity = $output->getVerbosity();
        $requirements = $input->getOption('requirements');
        if (empty($requirements)) {
            $requirements = null;
        }
        $dataSetNames = ($requirements !== null) ? $impMgr->getDataSetRequirements($requirements) : $impMgr->getDataSets();
        $dataSets = [];
        foreach ($dataSetNames as $dataSet) {
            $dataSets[$dataSet] = $impMgr->getDataSetImporter($dataSet);
        }
        if ($verbosity <= OutputInterface::VERBOSITY_QUIET) {
            return;
        }
        $output->writeln(($requirements !== null) ? ('Data sets required by <comment>'.$requirements.'</comment> :') : 'Available data sets :');
        foreach ($dataSets as $dataSet => $importer) {
            if ($verbosity >= OutputInterface::VERBOSITY_VERBOSE) {
                $clazz = get_class($impMgr->getDataSetImporter($dataSet));
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
