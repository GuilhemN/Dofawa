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
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsManagerBundle:Item');

        $pets = $repo->getAllPetsNotification();
        $now = new DateTime("now");

        foreach ($pets as $pet) {
            $nextMeal = $pet->getLastMeal()->modify('+'.$pet->getItemTemplate()->getMinFeedInterval().' hour');
            if( ($nextMeal < $now) && ($nextMeal > $pet->getLastNotification()) ){
                $this->get('notification_manager')->addNotification($pet, 'pets.hungry', $pet->getOwner());
            }
        }

    }
}