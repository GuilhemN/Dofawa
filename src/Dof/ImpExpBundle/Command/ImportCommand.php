<?php
namespace Dof\ImpExpBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

class ImportCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('dof:imp-exp:import')
			->setDescription('Imports data from outer sources')
			->addOption('skip', 'k', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Skip data sets (assume they were already imported)')
			->addOption('all', 'a', InputOption::VALUE_NONE, 'Import all registered data sets')
			->addOption('only', 'o', InputOption::VALUE_NONE, 'Import only the specified data sets (don\'t manage requirements)')
			->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Perform a dry run (tell importers not to write data)')
			->addOption('dry-dry-run', 'D', InputOption::VALUE_NONE, 'Perform a dry dry run (don\'t actually run any importer)')
			->addOption('one-process', '1', InputOption::VALUE_NONE, 'Don\'t fork (may cause memory fragmentation issues in big imports)')
            ->addArgument('data-sets', InputArgument::IS_ARRAY, 'Data sets to import');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        $impMgr = $this->getContainer()->get('dof_imp_exp.import_manager');
        $impMgr->setWithRequirements(!$input->getOption('only'));
        $impMgr->setRunImporters(!$input->getOption('dry-dry-run'));
		$impMgr->setFork(!$input->getOption('one-process'));
        foreach ($input->getOption('skip') as $skip)
            $impMgr->markAsImported($skip);
        $flags = 0;
        if ($input->getOption('dry-run'))
            $flags |= ImporterFlags::DRY_RUN;
        $dataSets = $input->getOption('all') ? $impMgr->getDataSets() : $input->getArgument('data-sets');
        if ($output->getVerbosity() <= OutputInterface::VERBOSITY_QUIET) {
            $output = null;
            $progress = null;
        } else
            $progress = $this->getHelperSet()->get('progress');
        foreach ($dataSets as $dataSet)
            $impMgr->import($dataSet, $flags, $output, $progress);
	}
}
