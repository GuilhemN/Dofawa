<?php
namespace Dof\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\MainBundle\Entity\Notification;

class CreateProgrammedNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('dof:user:programmed-notifications')
        ->setDescription('Create the programmed notifications')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('DofUserBundleProgrammedNotification');

        $notifications = $repo->findReadyNotifications();
        foreach($notifications as $pn) {
            $n = new Notification();
            $n
                ->setOwner($pn->getOwner())
                ->setType($pn->getType())
                ->setClass($pn->getClass())
                ->setClassId($pn->getClassId());
            $em->persist($n);
            $pn->setCreated(true);
        }
        $em->flush();
    }
}
