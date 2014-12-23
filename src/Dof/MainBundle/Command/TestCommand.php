<?php
namespace Dof\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use XN\Grammar\Reader;
use XN\Grammar\StringReader;
use Dof\ItemsBundle\Criteria\CriteriaParser;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('guigui:test')
        ->setDescription('Test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $x = -4390946;
        $w1 = ($x >> 16) & 0xFFFF;
        $w2 = $x & 0xFFFF;
        $output->writeLn(($w1 & 0x8000) ? ($w1 - 0x10000) : $w1);
        $output->writeLn(($w2 & 0x8000) ? ($w2 - 0x10000) : $w2);
    }
}
