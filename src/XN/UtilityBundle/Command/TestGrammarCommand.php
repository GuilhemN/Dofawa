<?php
namespace XN\UtilityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use XN\Grammar as Gr;

class TestGrammarCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('xn:grammar:test')
			->setDescription('Tests the grammar system');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        new Gr\StringReader("");
        $utime = microtime(true);
        $reader = new Gr\StringReader("foo bar azerty xoxoxo");
        while (($word = $reader->eatRegex('/\w+/A')) !== null) {
            $output->writeln($word);
            $reader->eatWhiteSpace();
        }
        $output->writeln("Time : <comment>" . number_format((microtime(true) - $utime) * 1000, 2) . " ms</comment>");
        $factory = $this->getContainer()->get('xn.grammar');
		$calcCtx = new Gr\Test\Calculator();
		$calcCtx->constants['cos'] = 'cos';
		$calcCtx->constants['pi'] = M_PI;
		$calcParser = $factory->createParser($calcCtx);
		$reader = new Gr\StringReader('cos pi * (2 ** 4 + 5) * 2'); // should evaluate to -42
		$utime = microtime(true);
		if ($calcParser->primary($reader, $result))
			$output->writeln($reader . ' = <info>' . $result . '</info>');
		else
			$output->writeln($reader . ' = <error>error</error>');
        $output->writeln("Time : <comment>" . number_format((microtime(true) - $utime) * 1000, 2) . " ms</comment>");
	}
}
