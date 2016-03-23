<?php

namespace Dof\Bundle\ImpExpBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use Dof\Bundle\ImpExpBundle\ImportManager;

class ImportCommand extends Command
{
    private $importManager;

    public function __construct(ImportManager $importManager)
    {
        $this->importManager = $importManager;
    }

    protected function configure()
    {
        $this
            ->setName('dof:imp-exp:import')
            ->setDescription('Imports data from outer sources')
            ->addOption('skip', 'k', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Skip data sets (assume they were already imported)')
            ->addOption('group', 'g', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Filter by groups')
            ->addOption('exclude-group', 'x', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Filter by groups')
            ->addOption('all', 'a', InputOption::VALUE_NONE, 'Import all registered data sets')
            ->addOption('only', 'o', InputOption::VALUE_NONE, 'Import only the specified data sets (don\'t manage requirements)')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Perform a dry run (tell importers not to write data)')
            ->addOption('dry-dry-run', 'D', InputOption::VALUE_NONE, 'Perform a dry dry run (don\'t actually run any importer)')
            ->addOption('one-process', '1', InputOption::VALUE_NONE, 'Don\'t fork (may cause memory fragmentation issues in big imports)')
            ->addArgument('data-sets', InputArgument::IS_ARRAY, 'Data sets to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importManager->setWithRequirements(!$input->getOption('only'));
        $this->importManager->setRunImporters(!$input->getOption('dry-dry-run'));
        $this->importManager->setFork(!$input->getOption('one-process'));
        foreach ($input->getOption('skip') as $skip) {
            $this->importManager->markAsImported($skip);
        }
        foreach ($input->getOption('exclude-group') as $group) {
            $this->importManager->markGroupAsImported($group);
        }
        $flags = 0;
        if ($input->getOption('dry-run')) {
            $flags |= ImporterFlags::DRY_RUN;
        }
        $dataSets = $input->getOption('all') ? $this->importManager->getDataSets() : $input->getArgument('data-sets');
        $groups = $input->getOption('group');
        if (!empty($groups)) {
            $dataSets = array_filter($dataSets, function ($val) use ($groups) {
                foreach ($this->importManager->getDataSetGroups($val) as $group) {
                    if (in_array($group, $groups)) {
                        return true;
                    }
                }
            });
        }

        if ($output->getVerbosity() <= OutputInterface::VERBOSITY_QUIET) {
            $output = null;
            $progress = null;
        } else {
            $progress = $this->getHelperSet()->get('progress');
        }
        foreach ($dataSets as $dataSet) {
            $this->importManager->import($dataSet, $flags, $output, $progress);
        }
    }
}
