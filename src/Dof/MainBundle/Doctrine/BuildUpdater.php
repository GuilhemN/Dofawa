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
                $stuff->getLook()->setBreed($ent->getBreed());
                $em->persist($stuff->getLook());
                $em->persist($stuff);
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
        $look = $ent->getLook();

        $face = $faces->findOneBy(array('breed' => $look->getBreed(), 'label' => $ent->getFaceLabel(), 'gender' => $look->getGender()));
        $look->setFace($face);

        $em->persist($look);
    }
}
