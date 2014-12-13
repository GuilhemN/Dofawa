<?php
namespace Dof\ItemsManagerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ItemsManagerBundle\Entity\Item;
use Dof\MainBundle\Entity\Notification;
use Dof\MainBundle\NotificationType;

class PetsManagerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('manager:pets')
            ->setDescription('Notifier les utilisateurs si leurs familiers ont faim.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $nm = $this->getContainer()->get('notification_manager');
        $repo = $em->getRepository('DofItemsManagerBundle:Pet');

        $pets = $repo->getAllPetsNotification();
        $now = new \DateTime("now");

        $notifs = 0;
        foreach ($pets as $pet) {
            $lastMeal = $pet->getLastMeal();

            $nextMeal = $lastMeal->modify('+' . $pet->getItemTemplate()->getMinFeedInterval() . ' hour');
            if($nextMeal < $now && $nextMeal > $pet->getLastNotification()){
                $nm->addNotification($pet, 'pets.hungry', $pet->getOwner());
                $notifs++;
            }
        }

        $output->writeln('<info>' . $notifs .' notifications ont été envoyées.</info>');
    }
}
