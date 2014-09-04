<?php

namespace Dof\MainBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Dof\UserBundle\Entity\User;
use Dof\UserBundle\Entity\Badge;
use Dof\MainBundle\Entity\Notification;
use Dof\MainBundle\NotificationType;

class BadgeManager
{

    /**
     * @var ContainerInterface
     */
    private $di;

    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;
    }

    public function addBadge($slug, $user = null){
        if(!($user instanceof User)){
            $user = $this->di->get('security.context')->getToken()->getUser();
            if($user === null)
                return ;
        }

        $em = $this->di->get('doctrine')->getEntityManager();

        $badge = $em->getRepository('DofMainBundle:Badge')->findOneBySlugWithLevels($slug);
        if($badge !== null){
            $uBadge = $em->getRepository('DofUserBundle:Badge')->findOneBy(array('badge' => $badge, 'owner' => $user));

            if($uBadge === null){
                $uBadge = new Badge();
                $uBadge->setBadge($badge);
                $uBadge->setOwner($user);
            }

            $uBadge->setCount($uBadge->getCount() + 1);
            $count = $uBadge->getCount();

            foreach($badge->getLevels() as $level)
                if($level->getMinCount() == $count){
                    $nm = $this->di->get('notification_manager');
                    $nm->addNotification($level, 'badge.receive', $user);
                    break;
                }

            $em->persist($uBadge);
            $em->flush();
        }
    }

}
