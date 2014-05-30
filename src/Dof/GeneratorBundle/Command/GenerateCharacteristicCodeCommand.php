<?php
namespace Dof\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCharacteristicCodeCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('generate:dof:characteristic')
			->setDescription('Generate characteristic-specific code');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
	}
}
