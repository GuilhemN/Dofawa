<?php

namespace Dof\MainBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Dof\UserBundle\User;
use Dof\UserBundle\Badge;

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
            if($user ===  null)
                return ;
        }

        $em = $this->di->get('doctrine')->getEntityManager();

        $badge = $em->getRepository('DofMainBundle:Badge')->findOneBySlug($slug);
        if($badge !== null){
            $uBadge = $em->getRepository('DofUserBundle:Badge')->findOneBy(array('badge' => $badge, 'owner' => $user));

            if($uBadge === null){
                $uBadge = new Badge();
                $uBadge->setBadge($badge);
                $uBadge->setOwner($user);
            }

            $uBadge->setCount($uBadge->getCount() + 1);
            $em->persist($uBadge);
        }
    }

}
