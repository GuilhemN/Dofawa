<?php

namespace XN\UtilityBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use XN\Metadata\FileInterface;

class FileUpdater
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof FileInterface)
            $this->upload($ent);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof FileInterface)
            $this->upload($ent);
    }

    public function preRemove(LifecycleEventArgs $args){
        $ent = $args->getEntity();
        if ($ent instanceof FileInterface)
            $this->remove($ent);
    }

    protected function upload(FileInterface $ent)
    {
        // the file property can be empty if the field is not required
        if (null === $ent->getFile())
            return;

        if(!empty($ent->getPath()))
            $this->remove($ent);

        // move takes the target directory and then the
        // target filename to move to
        $name = $ent->generateFileName();
        $ent->getFile()->move(
            $ent->getUploadRootDir(),
            $name
        );

        // set the path property to the filename where you've saved the file
        $ent->setPath($name);

        // clean up the file property as you won't need it anymore
        $ent->setFile(null);
    }

    protected function remove(FileInterface $ent) {
        @unlink($ent->getAbsolutePath());
    }
}
