<?php

namespace Dof\Bundle\GraphicsBundle;

use Doctrine\Common\Persistence\ObjectManager;

use Dof\Bundle\ItemBundle\Entity\SkinnedEquipmentTemplate;
use Dof\Bundle\ItemBundle\Entity\SkinnedEquipmentTemplateRepository;
use Dof\Bundle\ItemBundle\Entity\AnimalTemplate;
use Dof\Bundle\ItemBundle\Entity\AnimalTemplateRepository;
use Dof\Bundle\CharacterBundle\Entity\Breed;
use Dof\Bundle\CharacterBundle\Entity\BreedRepository;
use Dof\Bundle\CharacterBundle\Entity\Face;
use Dof\Bundle\CharacterBundle\Entity\FaceRepository;

use Dof\Bundle\ItemBundle\ItemSlot;
use Dof\Bundle\CharacterBundle\Gender;

class BPCLIdentifier
{
    /**
     * @var SkinnedEquipmentTemplateRepository
     */
    private $skinnedEquipmentTemplates;

    /**
     * @var AnimalTemplateRepository
     */
    private $animalTemplates;

    /**
     * @var BreedRepository
     */
    private $breeds;

    /**
     * @var FaceRepository
     */
    private $faces;

    /**
     * @var LivingItemFactory
     */
    private $livingItemFactory;

    /**
     * @var ChameleonDragoturkey
     */
    private $chameleonDragoturkey;

    public function __construct(ObjectManager $dm, LivingItemFactory $livingItemFactory, ChameleonDragoturkey $chameleonDragoturkey)
    {
        $this->skinnedEquipmentTemplates = $dm->getRepository('DofItemBundle:SkinnedEquipmentTemplate');
        $this->animalTemplates = $dm->getRepository('DofItemBundle:AnimalTemplate');
        $this->breeds = $dm->getRepository('DofCharacterBundle:Breed');
        $this->faces = $dm->getRepository('DofCharacterBundle:Face');
        $this->livingItemFactory = $livingItemFactory;
        $this->chameleonDragoturkey = $chameleonDragoturkey;
    }

    public function identify($entityLooks, $basicPCLooks0 = null)
    {
        if (!is_array($entityLooks)) {
            $retval = $this->identify([ $entityLooks ], ($basicPCLooks0 === null) ? [ new BasicPCLook() ] : [ $basicPCLooks0 ]);
            return $retval[0];
        }
        $animalLooks = array();
        $basicPCLooks = array();
        $skins = array();
        $bones = array();
        foreach ($entityLooks as $key => $look) {
            if ($look !== null) {
                $basicPCLooks[$key] = ($basicPCLooks0 === null || !isset($basicPCLooks0[$key])) ? new BasicPCLook() : $basicPCLooks0[$key];
                $skins += array_flip($look->getSkins());
                $aniLook = EntityLookTransforms::locateAnimalFromPC($look);
                if ($aniLook !== null) {
                    $animalLooks[$key] = $aniLook;
                    $bones[$aniLook->getBone()] = 1;
                }
            }
        }
        $skins = array_keys($skins);
        $bones = array_keys($bones);
        $skins = $this->skinnedEquipmentTemplates->findBySkinIds($skins)
            + $this->breeds->findBySkinIds($skins)
            + $this->faces->findBySkinIds($skins)
            + $this->livingItemFactory->createFromSkin($skins);
        $bones = $this->animalTemplates->findByBoneIds($bones);
        foreach ($entityLooks as $key => $look) {
            if ($look !== null) {
                $result = $basicPCLooks[$key];
                foreach ($look->getColors() as $index => $color)
                    $result->setColor($index, $color);
                foreach ($look->getSkins() as $skin) {
                    if (isset($skins[$skin])) {
                        $obj = $skins[$skin];
                        if ($obj instanceof Breed) {
                            $result->setBreed($obj);
                            if ($skin == $obj->getMaleSkin() || $skin == $obj->getMaleLodefSkin())
                                $result->setGender(Gender::MALE);
                            elseif ($skin == $obj->getFemaleSkin() || $skin == $obj->getFemaleLodefSkin())
                                $result->setGender(Gender::FEMALE);
                        } elseif ($obj instanceof Face)
                            $result->setFace($obj);
                        elseif ($obj instanceof SkinnedEquipmentTemplate || $obj instanceof LivingItem) {
                            switch ($obj->getType()->getSlot()) {
                                case ItemSlot::WEAPON:
                                    $result->setWeapon($obj);
                                    break;
                                case ItemSlot::SHIELD:
                                    $result->setShield($obj);
                                    break;
                                case ItemSlot::HAT:
                                    $result->setHat($obj);
                                    break;
                                case ItemSlot::CLOAK:
                                    $result->setCloak($obj);
                                    break;
                                default:
                                    $result->addExtraSkin($skin);
                                    break;
                            }
                        }
                    } else
                        $result->addExtraSkin($skin);
                }
            }
        }
        return $basicPCLooks;
    }
}
