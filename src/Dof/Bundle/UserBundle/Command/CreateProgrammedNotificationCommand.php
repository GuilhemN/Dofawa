<?php

namespace Dof\Bundle\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\MainBundle\Entity\Notification;

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
        $repo = $em->getRepository('DofUserBundle:ProgrammedNotification');

        $notifications = $repo->findReadyNotifications();
        $i = 0;
        foreach ($notifications as $pn) {
            $n = new Notification();
            $n
                ->setOwner($pn->getOwner())
                ->setType($pn->getType())
                ->setClass($pn->getClass())
                ->setClassId($pn->getClassId());
            $em->persist($n);
            $em->remove($pn);
            $i++;
        }
        $em->flush();

        $output->writeLn(sprintf('<info>%s notification(s) ont été crée(s).', $i));
    }
}
