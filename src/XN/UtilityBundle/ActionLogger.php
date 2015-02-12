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
    private $repo;

    private $results;

    public function __construct(ObjectManager $em, SecurityContext $sc) {
        $this->em = $em;
        $this->sc = $sc;
        $this->repo = $em->getRepository('XNUtilityBundle:LoggedAction');
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

    public function getLastByTypes($types) {
        $types = (array) $types;
        $user = $this->getUser();
        if(!is_object($user))
            return;
        $serial = serialize($types);
        if(isset($this->results[$serial]))
            return $this->results[$serial];
        $result = $this->repo->findLastAction($user, $types);
        $this->results[$serial] = $result;
        return $result;
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
}
