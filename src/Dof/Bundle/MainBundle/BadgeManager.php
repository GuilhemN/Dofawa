<?php

namespace Dof\Bundle\MainBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Dof\Bundle\UserBundle\Entity\User;
use Dof\Bundle\UserBundle\Entity\Badge;
use Dof\Bundle\MainBundle\Entity\Notification;

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

    public function addBadge($slug, $user = null)
    {
        if (!($user instanceof User)) {
            $user = $this->di->get('security.context')->getToken()->getUser();
            if ($user === null) {
                return;
            }
        }

        $em = $this->di->get('doctrine')->getEntityManager();

        $badge = $em->getRepository('DofMainBundle:Badge')->findOneBySlugWithLevels($slug);
        if ($badge !== null) {
            $uBadge = $em->getRepository('DofUserBundle:Badge')->findOneBy(array('badge' => $badge, 'owner' => $user));

            if ($uBadge === null) {
                $uBadge = new Badge();
                $uBadge->setBadge($badge);
                $uBadge->setOwner($user);
            }

            $uBadge->setCount($uBadge->getCount() + 1);
            $count = $uBadge->getCount();

            foreach ($badge->getLevels() as $level) {
                if ($level->getMinCount() == $count) {
                    $notification = new Notification();
                    $notification
                        ->setType('badge.receive')
                        ->setOwner($user)
                        ->setEntity($level);
                    $em->persist($notification)->flush();
                    break;
                }
            }

            $em->persist($uBadge);
            $em->flush();
        }
    }
}
