<?php
namespace XN\UtilityBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use XN\UtilityBundle\Entity\LoggedAction;

class ActionLogger
{
    private $em;
    private $sc;

    private $actions;

    public function __construct(ObjectManager $em, SecurityContext $sc) {
        $this->em = $em;
        $this->sc = $sc;
    }

    public function set($key, array $context = array(), AdvancedUserInterface $user = null) {
        $this->load();
        if($user === null){
            $user = $this->getUser();
            if($user === null)
                return $this;
        }
        $action = new LoggedAction();
        $action
            ->setKey($key)
            ->setContext($context)
            ->setOwner($user);
        $this->em->persist($action);
        $this->em->flush($action);
        return $this;
    }

    public function getLastByTypes(array $types) {
        $this->load();
        foreach($this->actions as $key => $context)
            if(in_array($key, $types))
                return [ $key, $context ];
        return null;
    }

    protected function getUser() {
        if(!($token = $this->sc->getToken() or !(($user = $token->getUser()) instanceof AdvancedUserInterface)))
            return null;
        return $user;
    }

    protected function load() {
        if($this->actions !== null)
            return;

        $user = $this->getUser();
        if($user !== null)
            $this->actions = $this->em->getRepository('XNUtilityBundle:LoggedAction')->findLastActions($user);
        else
            $this->actions = [];
    }
}
