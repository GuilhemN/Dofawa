<?php

namespace Dof\Bundle\User\CharacterBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Dof\Bundle\User\CharacterBundle\Entity\Stuff;
use Dof\Bundle\ItemBundle\CharacteristicsMetadata;
use XN\Common\ServiceWithContainer;

class BuildManager
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

    public function reloadStuff(Stuff $stuff)
    {
        $em = $this->objectManager;
        $stuffs = $em->getRepository('DofUserCharacterBundle:Stuff');

        return $this->transformStuff($stuffs->findOneById($stuff->getId()));
    }

    public function getStuffBySlug($slug)
    {
        $em = $this->objectManager;
        $repository = $em->getRepository('DofUserCharacterBundle:Stuff');

        $stuff = $repository->findOneBySlug($slug);

        if ($stuff === null) {
            return;
        }

        return $this->transformStuff($stuff);
    }

    public function getBySlugs($user, $character, $stuff)
    {
        $em = $this->objectManager;
        $repository = $em->getRepository('DofUserCharacterBundle:Stuff');

        $stuff = $repository->findParamConverter($user, $character, $stuff);

        return $this->transformStuff($stuff);
    }

    public function transformStuff(Stuff $stuff)
    {
        $em = $this->objectManager;
        $faces = $em->getRepository('DofCharacterBundle:Face');

        $face = $faces->findOneBy(array('breed' => $stuff->getCharacter()->getBreed(), 'label' => $stuff->getFaceLabel(), 'gender' => $stuff->getLook()->getGender()));
        $stuff->getLook()->setFace($face);
        $stuff->getLook()->setBreed($stuff->getCharacter()->getBreed());

        return $stuff;
    }

    public function getCharacteristics(Stuff $stuff, &$bonus)
    {
        $em = $this->objectManager;
        $return = array_map(function () { return 0; }, CharacteristicsMetadata::getAll());
        $sets = [];
        $bonus = [];

        foreach ($stuff->getItems() as $v) {
            foreach ($v as $item) {
                if ($item === null) {
                    continue;
                }
                foreach ($item->getCharacteristics() as $k => $v) {
                    $return[$k] += $v;
                }

                // Panos et nombres d'items associés dans le stuff
                if ($item->getItemTemplate()->getSet() !== null) {
                    ++$sets[$item->getItemTemplate()->getSet()->getId()];
                }
            }
        }

        // Bonus de panos ($bonus)
        $setBonusRepo = $em->getRepository('DofItemBundle:ItemSetCombination');
        foreach ($sets as $set => $v) {
            if (($b = $setBonusRepo->findOneBy(array('set' => $set, 'itemCount' => $v))) !== null) {
                $bonus[] = $b;
            }
        }

        foreach ($bonus as $b) {
            foreach ($b->getCharacteristics() as $k => $v) {
                $return[$k] += $v;
            }
        }

        $return['vitality'] += $stuff->getVitality();
        $return['wisdom'] += $stuff->getWisdom();
        $return['strength'] += $stuff->getStrength();
        $return['intelligence'] += $stuff->getIntelligence();
        $return['chance'] += $stuff->getChance();
        $return['agility'] += $stuff->getAgility();

        $return['ap'] += 6;
        $return['mp'] += 3;
        $return['prospecting'] += 100;
        if ($stuff->getCharacter()->getLevel() >= 100) {
            $return['ap'] += 1;
        }

        return $return;
    }
}
