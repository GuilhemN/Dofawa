<?php

namespace Dof\MainBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

use Dof\BuildBundle\Entity\Stuff;
use Dof\BuildBundle\Entity\PlayerCharacter;

class BuildUpdater
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof PlayerCharacter) {
            $em = $args->getEntityManager();

            foreach($ent->getStuffs() as $stuff){
                $this->updateStuff($stuff, $em);
                $stuff->getLook()->setBreed($character->getBreed());
            }
        }
        elseif($ent instanceof Stuff){
            $em = $args->getEntityManager();
            $this->updateStuff($ent, $em);
        }
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->prePersist($args);
    }

    private function updateStuff(Stuff $ent, $em){
        $faces = $em->getRepository('DofCharactersBundle:Face');

        $face = $faces->findOneBy(array('breed' => $ent->getLook()->getBreed(), 'label' => $ent->getFaceLabel(), 'gender' => $ent->getLook()->getGender()));
        $ent->getLook()->setFace($face);

    }
}
