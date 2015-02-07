<?php
namespace Dof\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use Dof\Common\HTMLToBBCodeConverter;

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
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('DofCMSBundle:Article');
        $su = $this->getContainer()->get('xn.doctrine.sluggable_updater');

        $qb = $repo->createQueryBuilder('a');
        $qb->select('count(a)');
        $count = $qb->getQuery()->getSingleScalarResult();
        $progress = new ProgressBar($output, $count);

        // start and displays the progress bar
        $progress->start();

        $i = 0;
        while(1){
            $articles = $em->getRepository('DofCMSBundle:Article')->findBy([], ['id' => 'DESC'], 30, $i * 30);
            if(empty($articles))
                break;
            foreach($articles as $article) {
                $su->reassignSlug($article);
                $em->flush($article);
            }
            $progress->advance(count($articles));
            $em->clear();
            $i++;
        }
        $progress->finish();
        $output->writeLn();
    }
}
