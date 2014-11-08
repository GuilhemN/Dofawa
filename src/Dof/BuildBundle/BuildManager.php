<?php
namespace Dof\BuildBundle;

use XN\Common\ServiceWithContainer;

use Dof\BuildBundle\Entity\Stuff;
use Dof\UserBundle\Entity\User;

use Dof\ItemsBundle\CharacteristicsMetadata;

class BuildManager extends ServiceWithContainer
{
    public function getBySlugs($user, $character, $stuff){
        $em = $this->getEntityManager();
        $repository = $em->getRepository('DofBuildBundle:Stuff');
        return $repository->findParamConverter($user, $character, $stuff);
    }

    public function canSee(Stuff $stuff, $user = null){
        if($user !== null && !($user instanceof User))
            throw new \InvalidArgumentException('The canSee function of the build Manager only accept instance of User or null for $user.');
        if($user === null)
            $user = $this->getSecurityContext()->getToken()->getUser();

        return $stuff->isVisible() or $this->canWrite($stuff, $user);
    }

    public function canWrite(Stuff $stuff, $user = null){
        if($user !== null && !($user instanceof User))
            throw new \InvalidArgumentException('The canWrite function of the build Manager only accept instance of User or null for $user.');
        if($user === null)
            $user = $this->getSecurityContext()->getToken()->getUser();

        return $user == $stuff->getCharacter()->getOwner() || $this->getSecurityContext()->isGranted('ROLE_SUPER_ADMIN');
    }

    public function getCharacteristics(Stuff $stuff, &$bonus){
        $em = $this->getEntityManager();
        $return = array_map(function () { return 0; }, CharacteristicsMetadata::getAll());
        $sets = []; $bonus = [];

        foreach($stuff->getItems() as $item){
            foreach($item->getCharacteristics() as $k => $v)
                $return[$k] += $v;

            // Panos et nombres d'items associés dans le stuff
            if($item->getItemTemplate()->getSet() !== null)
                $sets[$item->getItemTemplate()->getId()]++;
        }

        // Bonus de panos ($bonus)
        $setBonusRepo = $em->getRepository('DofItemsBundle:ItemSetCombination');
        foreach($sets as $set => $v)
            if(($b = $setBonusRepo->findOneBy(array('set' => $set, 'itemCount' => $v))) !== null)
                $bonus[] = $b;

        foreach($bonus as $b)
            foreach($bonus->getCharacteristics() as $k => $v)
                $return[$k] += $v;

        $return['vitality'] += $stuff->getVitality();
        $return['wisdom'] += $stuff->getWisdom();
        $return['strength'] += $stuff->getStrength();
        $return['intelligence'] += $stuff->getIntelligence();
        $return['chance'] += $stuff->getChance();
        $return['agility'] += $stuff->getAgility();

        return $return;
    }
}
