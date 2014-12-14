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
            ->setName('dof:items-manager:pet-notifications')
            ->setDescription('Notifier les utilisateurs si leurs familiers ont faim.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $nm = $this->getContainer()->get('notification_manager');
        $repo = $em->getRepository('DofItemsManagerBundle:Pet');

        $pets = $repo->getAllPetsNotification();

        $notifs = 0;
        foreach ($pets as $pet) {
            $pet->setLastNotification(new DateTime());
            $nm->addNotification($pet, 'pets.hungry', $pet->getOwner());
            $notifs++;
        }
        $em->flush();

        $output->writeln('<info>' . $notifs .' notifications ont été envoyées.</info>');
    }
}
