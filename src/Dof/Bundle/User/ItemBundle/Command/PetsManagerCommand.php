<?php

namespace Dof\Bundle\User\ItemBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\MainBundle\Entity\Notification;

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
        $repo = $em->getRepository('DofUserItemBundle:Pet');
        $pets = $repo->getAllPetsNotification();

        $notifs = 0;
        foreach ($pets as $pet) {
            $pet->setLastNotification(new \DateTime());

            $notification = new Notification();
            $notification
                ->setType('pets.hungry')
                ->setOwner($pet->getOwner())
                ->setEntity($pet);
            $em->persist($notification);

            ++$notifs;
        }
        $em->flush();

        $output->writeln('<info>'.$notifs.' notifications ont été envoyées.</info>');
    }
}
