<?php
namespace Dof\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCharacteristicTraitCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('generate:dof:chara-trait')
			->setDescription('Générer le trait lié aux caractéristiques');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		
	}
}