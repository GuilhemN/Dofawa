<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use XN\Metadata\FileInterface;

class FileUpdater
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof FileInterface) {
            $ent->upload();
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof FileInterface) {
            $ent->preUpload();
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof FileInterface) {
            $ent->upload();
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof FileInterface) {
            $ent->removeUpload();
        }
    }
}
