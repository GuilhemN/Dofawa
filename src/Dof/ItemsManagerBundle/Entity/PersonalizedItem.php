<?php

namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\CharacteristicsTrait;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use Dof\BuildBundle\Entity\Stuff;
use Dof\ItemsBundle\Entity\EquipmentTemplate;

use Dof\ItemsBundle\PrimaryBonusInterface;
use Dof\ItemsBundle\PrimaryBonusTrait;

/**
* Item
*
* @ORM\Table(name="dof_user_items")
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\PersonalizedItemRepository")
*/
class PersonalizedItem implements IdentifiableInterface, TimestampableInterface, PrimaryBonusInterface
{
    use CharacteristicsTrait, TimestampableTrait, PrimaryBonusTrait;

    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @var Stuff
    *
    * @ORM\ManyToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", inversedBy="items")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $stuffs;

    /**
    * @var Stuff
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsBundle\Entity\EquipmentTemplate", inversedBy="buildItems")
    * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $itemTemplate;

    /**
    * @var integer
    *
    * @ORM\Column(name="slot", type="integer")
    */
    private $slot;

    public function __construct()
    {
        $this->stuffs = new ArrayCollection();
    }

    /**
    * Get id
    *
    * @return integer
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Add stuffs
    *
    * @param Stuff $stuffs
    * @return PersonalizedItem
    */
    public function addStuff(Stuff $stuffs)
    {
        $this->stuffs[] = $stuffs;

        return $this;
    }

    /**
    * Remove stuffs
    *
    * @param Stuff $stuffs
    * @return PersonalizedItem
    */
    public function removeStuff(Stuff $stuffs)
    {
        $this->stuffs->removeElement($stuffs);

        return $this;
    }

    /**
    * Get stuffs
    *
    * @return Collection
    */
    public function getStuffs()
    {
        return $this->stuffs;
    }

    /**
    * Set itemTemplate
    *
    * @param ItemTemplate $itemTemplate
    * @return Item
    */
    public function setItemTemplate(EquipmentTemplate $itemTemplate)
    {
        $this->itemTemplate = $itemTemplate;

        return $this;
    }

    /**
    * Get itemTemplate
    *
    * @return ItemTemplate
    */
    public function getItemTemplate()
    {
        return $this->itemTemplate;
    }

    /**
    * Dof\BuildBundle\BuildSlot
    * Set slot
    *
    * @param integer $slot
    * @return Item
    */
    public function setSlot($slot)
    {
        $this->slot = $slot;

        return $this;
    }

    /**
    * Get slot
    *
    * @return integer
    */
    public function getSlot()
    {
        return $this->slot;
    }

    public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array()){
        foreach($primaryFields as $k => $v){
            if(!isset($caracts[$v['primaryBonus']]))
                $caracts[$v['primaryBonus']] = 0;

            $caracts[$v['primaryBonus']] += $this->{'get' . ucfirst($k)}() * $v['weight'];
        }

        return $caracts;
    }

    public function getCascadeForPrimaryBonus(){
        return $this->stuff;
    }
}
