<?php

namespace Dof\Bundle\User\ItemBundle;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Dof\Bundle\UserBundle\Entity\User;
use Dof\Bundle\ItemBundle\Entity\EquipmentTemplate;
use Dof\Bundle\User\ItemBundle\Entity as Ent;
use Dof\Bundle\ItemBundle\ItemSlot;

class ItemFactory
{
    /**
     * @var TokenStorageInterface
     */
    private $sc;

    public function __construct(TokenStorageInterface $sc)
    {
        $this->sc = $sc;
    }

    public function createItem(EquipmentTemplate $item, $percent = null, User $user = null)
    {
        if ($user === null) {
            $user = $this->sc->getToken()->getUser();
        }
        if (!($user instanceof User)) {
            return;
        }
        if ($percent === null) {
            $percent = 85;
        }
        $percent = intval($percent);

        switch ($item->getType()->getSlot()) {
            case ItemSlot::WEAPON:
                $ent = new Ent\Weapon();
                break;
            case ItemSlot::HAT:
            case ItemSlot::CLOAK:
            case ItemSlot::SHIELD:
                $ent = new Ent\SkinnedItem();
                break;
            case ItemSlot::PET:
                if ($item->isMount()) {
                    $ent = new Ent\Mount();
                } else {
                    $ent = new Ent\Pet();
                }
                break;
            case ItemSlot::MOUNT:
                $ent = new Ent\Mount();
                break;
            default:
                if ($item->isMount()) {
                    $ent = new Ent\Mount();
                } else {
                    $ent = new Ent\Item();
                }
                break;
        }

        $ent->setOwner($user);
        $ent->setItemTemplate($item);

        $caracts = $item->getCharacteristics();
        foreach ($caracts as $k => &$caract) {
            $min = $item->{'getMin'.ucfirst($k)}();
            $max = $item->{'getMax'.ucfirst($k)}();
            $caract = round($min + ($max - $min) * $percent / 100);
        }
        $ent->setCharacteristics($caracts, true);

        return $ent;
    }
}
