<?php
namespace Dof\Bundle\CharacterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\CharacterBundle\Entity\SpellRank;

class DumpSpellRankEffectsCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('dof:characters:dump-sre')
			->setDescription('Dump spell rank effects')
			->addArgument('slug', InputArgument::REQUIRED, 'Spell slug')
			->addArgument('rank', InputArgument::REQUIRED, 'Spell rank');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$dm = $this->getContainer()->get('doctrine')->getManager();
		
		$spell = $dm->getRepository('DofCharacterBundle:Spell')
			->findOneBy([ 'slug' => $input->getArgument('slug') ]);
		$rank = $dm->getRepository('DofCharacterBundle:SpellRank')
			->findOneBy([ 'spell' => $spell->getId(), 'rank' => $input->getArgument('rank') ]);

		$this->dump($rank, $output);
	}

	private function dump(SpellRank $rank, OutputInterface $output, $indent = '', $stack = [ ])
	{
		if (in_array($rank, $stack)) {
			$output->writeln($indent . '*RECURSE*');
			return;
		}
		foreach ($rank->getEffects() as $fx) {
			$output->writeln($indent . $fx->getPlainTextDescription(/*$this->getContainer()->get('translator')->getLocale()*/'fr', true, true));
			if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
				$output->writeln($indent . '  P1 : ' . $fx->getParam1());
				$output->writeln($indent . '  P2 : ' . $fx->getParam2());
				$output->writeln($indent . '  P3 : ' . $fx->getParam3());
				foreach ($fx->getFragments() as $frag)
					$output->writeln($indent . '  Fg : ' . $frag);
			}
			foreach ($fx->getFragments() as $frag) {
				if ($frag instanceof SpellRank)
					$this->dump($frag, $output, $indent . '  ', array_merge($stack, [ $rank ]));
			}
		}
	}
}
