<?php
namespace Dof\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        $su = $this->getContainer()->get('xn.doctrine.sluggable_updater');
        $i = 0;
        while(1){
            $articles = $em->getRepository('DofCMSBundle:Article')->findBy([], ['id' => 'DESC'], 60, $i * 60);
            $output->writeLn($i);
            if(empty($articles))
                break;
            foreach($articles as $article) {
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
            $em->flush();
            $em->clear();
            $i++;
        }
    }
}
