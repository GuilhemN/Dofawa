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
        ->addArgument('type', InputArgument::REQUIRED, 'category / quest')
        ->addArgument('slug', InputArgument::OPTIONAL, 'Quel est le slug ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('DofQuestBundle:Quest');

        $doc = new \DOMDocument();
        $doc->loadHTMLFile("http://www.krosmoz.com/fr/almanax");

        $dofus = $doc->getElementById('achievement_dofus');
        $mi = DOMUtils::getFirstElementByClassName($dofus, 'more-infos');
        $quest = DOMUtils::getFirstElementByNodeName($mi, 'p');
        $output->writeln('<info>HTML trouvé : ' . $quest->textContent . '</info>');
        preg_match('/^(Quête :)? (.*)$/', $quest->textContent, $title);

        $em
        ->createQuery('UPDATE DofQuestBundle:Quest s SET s.season = false WHERE s.type = :type')
        ->setParameter('type', QuestType::ALMANAX)
        ->execute();

        $quest = $repo->findOneByNameFr($title[2]);
        if($quest !== null){
            $quest->setSeason(true);
            $em->flush();

            $output->writeln('<info>Quête almanax du jour : ' . $quest->getName() . '</info>');
        }
        else
        $output->writeln('<error>La quête n\'a pas été trouvé.</error>');

    }
}
