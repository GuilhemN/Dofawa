<?php

namespace Dof\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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
        while (1) {
            $articles = $em->getRepository('DofCMSBundle:Article')->findBy([], ['id' => 'DESC'], 30, $i * 30);
            if (empty($articles)) {
                break;
            }
            foreach ($articles as $article) {
                $article->setDescriptionFr(HTMLToBBCodeConverter::process($article->getDescriptionFr()));
                $article->setDescriptionEn(HTMLToBBCodeConverter::process($article->getDescriptionEn()));
                $article->setDescriptionDe(HTMLToBBCodeConverter::process($article->getDescriptionDe()));
                $article->setDescriptionEs(HTMLToBBCodeConverter::process($article->getDescriptionEs()));
                $article->setDescriptionIt(HTMLToBBCodeConverter::process($article->getDescriptionIt()));
                $article->setDescriptionPt(HTMLToBBCodeConverter::process($article->getDescriptionPt()));
                $article->setDescriptionJa(HTMLToBBCodeConverter::process($article->getDescriptionJa()));
                $article->setDescriptionRu(HTMLToBBCodeConverter::process($article->getDescriptionRu()));

                $su->reassignSlug($article);
            }
            $progress->advance(count($articles));
            $em->flush();
            $em->clear();
            $i++;
        }
        $progress->finish();
        $output->writeLn('');
    }
}
