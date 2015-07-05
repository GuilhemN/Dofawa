<?php

namespace App\MainBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use App\UserBundle\RoleHelper;
use App\UserBundle\Entity\User;

class AuthenticationSuccessListener
{
    private $rh;

    public function __construct(RoleHelper $rh)
    {
        $this->rh = $rh;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data['user'] = array(
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'slug' => $user->getSlug(),
            'roles' => $this->rh->getCurrentRoles(),
        );

        $event->setData($data);
    }
}
