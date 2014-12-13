<?php
namespace Dof\QuestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QuestSeasonCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('dof:quest:change-season')
        ->setDescription('Change la saison d\'une quête ou catégorie de quête.')
        ->addArgument('value', InputArgument::OPTIONAL, 'Set the new value', 'false')
        ->addOption('category', 'c', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Slug d\'une catégorie de quêtes')
        ->addOption('quest', 'q', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Slug d\'une quête')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $value = $input->getArgument('value');
        if($value != ('false' or 'true' or 'null'))
            $value = 'false';

        $em = $this->getContainer()->get('doctrine')->getManager();
        $cRepo = $em->getRepository('DofQuestBundle:QuestCategory');
        $qRepo = $em->getRepository('DofQuestBundle:Quest');

        foreach ($input->getOption('category') as $category){
            $category = $cRepo->findBySlug($category);
            $em
                ->createQuery('UPDATE DofQuestBundle:Quest s SET s.season = :value WHERE s.category = :category')
                ->setParameter('value', $value)
                ->setParameter('category', $category)
                ->execute();
        }

        foreach ($input->getOption('quest') as $quest){
            $em
                ->createQuery('UPDATE DofQuestBundle:Quest s SET s.season = :value WHERE s.slug = :slug')
                ->setParameter('value', $value)
                ->setParameter('slug', $quest)
                ->execute();
        }


    }
}
