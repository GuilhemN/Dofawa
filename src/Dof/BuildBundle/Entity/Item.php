<?php

namespace Dof\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\CharacteristicsTrait;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use Dof\BuildBundle\Entity\Stuff;
use Dof\ItemsBundle\Entity\ItemTemplate;

use Dof\ItemsBundle\PrimaryBonusInterface;
use Dof\ItemsBundle\PrimaryBonusTrait;

/**
 * Item
 *
 * @ORM\Table(name="dof_build_item")
 * @ORM\Entity(repositoryClass="Dof\BuildBundle\Entity\ItemRepository")
 */
class Item implements IdentifiableInterface, TimestampableInterface, PrimaryBonusInterface
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
     * @ORM\ManyToOne(targetEntity="Dof\BuildBundle\Entity\Stuff", inversedBy="items")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $stuff;

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
     * Set stuff
     *
     * @param Stuff $stuff
     * @return Item
     */
    public function setStuff(Stuff $stuff)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return Stuff
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * Set itemTemplate
     *
     * @param ItemTemplate $itemTemplate
     * @return Item
     */
    public function setItemTemplate(ItemTemplate $itemTemplate)
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
}
