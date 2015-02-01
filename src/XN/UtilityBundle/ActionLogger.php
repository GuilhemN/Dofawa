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

    public function set($key, $entity = null, array $context = array(), AdvancedUserInterface $user = null) {
        $this->load();
        if($user === null){
            $user = $this->getUser();
            if($user === null)
                return $this;
        }
        $action = new LoggedAction();
        $action
            ->setKey($key)
            ->setEntity($entity)
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
        $token = $this->sc->getToken();
        if($token === null)
            return;
        $user = $token->getUser();
        if($user === null)
            return;
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
