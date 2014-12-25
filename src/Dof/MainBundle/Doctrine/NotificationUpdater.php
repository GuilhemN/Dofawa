<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Dof\MainBundle\Entity\Notification;
use XN\Persistence\IdentifiableInterface;

class NotificationUpdater
{
    /**
    * @var boolean
    *
    * Sometimes you may want to disable automatic parameter loading,
    * for example when importing data
    */
    private $enabled;

    public function __construct(){
        $this->enabled = true;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof Notification) {
            if($ent->getEntity() instanceof IdentifiableInterface)
                $ent
                ->setClass($em->getClassMetadata(get_class($ent->getEntity()))->getName())
                ->setClassId($ent->getId())
                ;
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        if ($ent instanceof Notification)
            if ($this->enabled) {
                $ent->setValid(true);
                if($ent->getClass() !== null && $ent->getClassId() !== null){
                    $entity = $em->getRepository($ent->getClass())->find($ent->getClassId());
                    $ent->setEntity($entity);
                    if($entity === null)
                        $ent->setValid(false);
                }
            }
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}
