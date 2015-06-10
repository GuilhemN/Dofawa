<?php

namespace Dof\Bundle\QuestBundle\Command;

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
        ->addArgument('value', InputArgument::OPTIONAL, 'Set the new value', 'null')
        ->addOption('category', 'c', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Slug d\'une catégorie de quêtes')
        ->addOption('quest', 'qu', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Slug d\'une quête')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $value = $input->getArgument('value');
        if ($value == 'false') {
            $value = false;
        } elseif ($value == 'true') {
            $value = true;
        } else {
            $value = null;
        }

        $em = $this->getContainer()->get('doctrine')->getManager();
        $cRepo = $em->getRepository('DofQuestBundle:QuestCategory');
        $qRepo = $em->getRepository('DofQuestBundle:Quest');

        foreach ($input->getOption('category') as $sCategory) {
            $category = $cRepo->findOneBySlug($sCategory);
            if ($category === null) {
                $output->writeln('<error>'.$sCategory.' n\'a pas été trouvé.</error>');
                continue;
            }

            $return = $em
                ->createQuery('UPDATE DofQuestBundle:Quest s SET s.season = :value WHERE s.category = :category')
                ->setParameter('value', $value)
                ->setParameter('category', $category)
                ->execute();
            $output->writeln('<info>'.$return.' quêtes affectées pour la catégorie '.$category->getName().'</info>');
        }

        foreach ($input->getOption('quest') as $quest) {
            $em
                ->createQuery('UPDATE DofQuestBundle:Quest s SET s.season = :value WHERE s.slug = :slug')
                ->setParameter('value', $value)
                ->setParameter('slug', $quest)
                ->execute();
            $output->writeln('<info>'.$return.' lignes affectées pour la quête '.$quest.'</info>');
        }
    }
}
