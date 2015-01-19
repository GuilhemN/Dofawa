<?php
namespace Dof\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Common\D2PContentProvider;

class D2PCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('dof:d2p:extract')
        ->setDescription('Extract a d2p file')
        ->addArgument('path', InputArgument::REQUIRED, 'Path of the d2p File')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $d2pcp = new D2PContentProvider();
        $d2pcp->process($input->getArgument('path'));

        $output->writeln($d2pcp->enumerate());
    }
}
