<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

use Dof\BuildBundle\Entity\Stuff;
use Dof\BuildBundle\Entity\PlayerCharacter;

class SetUpdater
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof PlayerCharacter) {
            $em = $args->getEntityManager();

            foreach($ent->getStuffs() as $stuff){
                $stuff->getLook()->setBreed($character->getBreed());
            }
        }
        elseif($ent instanceof Stuff){
            $em = $args->getEntityManager();
            $faces = $em->getRepository('DofCharactersBundle:Face');

            $face = $faces->findOneBy(array('breed' => $ent->getLook()->getBreed(), 'label' => $ent->getFaceLabel(), 'gender' => $ent->getLook()->getGender()));
            $ent->getLook()->setFace($face);
        }
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->prePersist($args);
    }
}
