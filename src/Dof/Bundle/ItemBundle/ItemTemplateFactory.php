<?php

namespace Dof\Bundle\ItemBundle;

use Dof\Bundle\ItemBundle\Entity as Ent;

class ItemTemplateFactory
{
    private function __construct()
    {
    }

    /**
     * Create a new item template.
     *
     * @param int $slot
     * @param int $typeId
     *
     * @return Ent\ItemTemplate
     */
    public static function createItemTemplate($slot = null, $typeId = null)
    {
        switch ($slot) {
            case ItemSlot::AMULET:
            case ItemSlot::RING:
            case ItemSlot::BELT:
            case ItemSlot::BOOTS:
            case ItemSlot::DOFUS:
            case ItemSlot::MUTATION:
            case ItemSlot::BOOST:
            case ItemSlot::BLESSING:
            case ItemSlot::CURSE:
            case ItemSlot::RP_BUFF:
            case ItemSlot::FOLLOWER:
            case ItemSlot::SIDEKICK:
                return new Ent\EquipmentTemplate();
            case ItemSlot::WEAPON:
                return new Ent\WeaponTemplate();
            case ItemSlot::USEABLE:
                switch ($typeId) {
                    case 97: // Mount certificate
                        return new Ent\MountTemplate();
                    default:
                        return new Ent\UseableItemTemplate();
                }
            case ItemSlot::SHIELD:
            case ItemSlot::HAT:
            case ItemSlot::CLOAK:
                return new Ent\SkinnedEquipmentTemplate();
            case ItemSlot::PET:
                switch ($typeId) {
                    case 121: // Petsmount
                        return new Ent\MountTemplate();
                    default:
                        return new Ent\PetTemplate();
                }
            case ItemSlot::MOUNT:
                return new Ent\MountTemplate();
            case ItemSlot::LIVING_ITEM:
                switch ($typeId) {
                    case 177: // [!] Objet d'apparât
                        return new Ent\SkinnedEquipmentTemplate();
                    default:
                        return new Ent\ItemTemplate();
                }
            default: // RESOURCE, QUEST
                return new Ent\ItemTemplate();
        }
    }
}
