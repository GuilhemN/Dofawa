<?php
namespace Dof\ItemsManagerBundle;

use Symfony\Component\Security\Core\SecurityContext;

use Dof\UserBundle\Entity\User;
use Dof\ItemsBundle\Entity\EquipmentTemplate;
use Dof\ItemsManagerBundle\Entity as Ent;

use Dof\ItemsBundle\ItemSlot;

class ItemFactory {

    /**
    * @var Symfony\Component\Security\Core\SecurityContext
    */
    private $sc;

    public function __construct(SecurityContext $sc) {
        $this->sc = $sc;
    }

    public function createItem(EquipmentTemplate $item, $percent = null, User $user = null){
        if($user === null)
            $user = $this->sc->getToken()->getUser();
        if(!($user instanceOf User))
            return;
        if($percent === null)
            $percent = 80;
        $percent = intval($percent);


        switch ($item->getType()->getSlot()) {
            case ItemSlot::WEAPON:
            $ent = new Ent\Weapon();
            case ItemSlot::PET:
            switch ($item->getType()->getId()) {
                case 121: // Petsmount
                $ent = new Ent\Mount();
                default:
                $ent = new Ent\Pet();
            }
            case ItemSlot::MOUNT:
            $ent = new Ent\Mount();
            default:
            $ent = new Ent\Item();
        }

        $ent->setOwner($user);
        $ent->setItemTemplate($itemTemplate);

        $caracts = $item->getCharacteristics();
        foreach($caracts as $k => &$caract){
            $min = $item->{'getMin' . ucfirst($k)}();
            $max = $item->{'getMax' . ucfirst($k)}();
            $caract = round($min + ($max - $min) * $percent / 100);
        }
        $ent->setCharacteristics($caracts, true);

        return $ent;
    }
}
