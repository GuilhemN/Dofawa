<?php
namespace Dof\Bundle\User\ItemBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\User\ItemBundle\Entity\Item;
use Dof\Bundle\MainBundle\Entity\Notification;
use Dof\Bundle\MainBundle\NotificationType;

class DelOldPetsNotificationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dof:items-manager:del-old-pet-notifications')
            ->setDescription('Supprime toutes les anciennes notifications de familiers lues.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifs = $repo->delOldPetsNotifications();

        $output->writeln('<info>' . $notifs .' notifications ont été supprimées.</info>');
    }
}