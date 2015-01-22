<?php

namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

use Dof\ItemsBundle\Entity\ItemTemplate;

/**
 * Craft
 *
 * @ORM\Table(name="dof_user_crafts")
 * @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\CraftRepository")
 */
class Craft implements IdentifiableInterface, OwnableInterface, TimestampableInterface
{
    use OwnableTrait, TimestampableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @var ItemTemplate
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemsBundle\Entity\ItemTemplate")
    */
    private $itemTemplate;

    /**
     * @var integer
     *
     * @ORM\Column(name="index_", type="integer")
     */
    private $index;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity1", type="integer")
     */
    private $quantity1;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity2", type="integer")
     */
    private $quantity2;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity3", type="integer")
     */
    private $quantity3;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity4", type="integer")
     */
    private $quantity4;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity5", type="integer")
     */
    private $quantity5;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity6", type="integer")
     */
    private $quantity6;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity7", type="integer")
     */
    private $quantity7;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity8", type="integer")
     */
    private $quantity8;


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
    * Set itemTemplate
    *
    * @param ItemTemplate $itemTemplate
    * @return Craft
    */
    public function setItemTemplate(ItemTemplate $item)
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
    * Set index
    *
    * @param integer $index
    * @return Craft
    */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
    * Get index
    *
    * @return integer
    */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set quantity1
     *
     * @param integer $quantity1
     * @return Craft
     */
    public function setQuantity1($quantity1)
    {
        $this->quantity1 = $quantity1;

        return $this;
    }

    /**
     * Get quantity1
     *
     * @return integer
     */
    public function getQuantity1()
    {
        return $this->quantity1;
    }

    /**
     * Set quantity2
     *
     * @param integer $quantity2
     * @return Craft
     */
    public function setQuantity2($quantity2)
    {
        $this->quantity2 = $quantity2;

        return $this;
    }

    /**
     * Get quantity2
     *
     * @return integer
     */
    public function getQuantity2()
    {
        return $this->quantity2;
    }

    /**
     * Set quantity3
     *
     * @param integer $quantity3
     * @return Craft
     */
    public function setQuantity3($quantity3)
    {
        $this->quantity3 = $quantity3;

        return $this;
    }

    /**
     * Get quantity3
     *
     * @return integer
     */
    public function getQuantity3()
    {
        return $this->quantity3;
    }

    /**
     * Set quantity4
     *
     * @param integer $quantity4
     * @return Craft
     */
    public function setQuantity4($quantity4)
    {
        $this->quantity4 = $quantity4;

        return $this;
    }

    /**
     * Get quantity4
     *
     * @return integer
     */
    public function getQuantity4()
    {
        return $this->quantity4;
    }

    /**
     * Set quantity5
     *
     * @param integer $quantity5
     * @return Craft
     */
    public function setQuantity5($quantity5)
    {
        $this->quantity5 = $quantity5;

        return $this;
    }

    /**
     * Get quantity5
     *
     * @return integer
     */
    public function getQuantity5()
    {
        return $this->quantity5;
    }

    /**
     * Set quantity6
     *
     * @param integer $quantity6
     * @return Craft
     */
    public function setQuantity6($quantity6)
    {
        $this->quantity6 = $quantity6;

        return $this;
    }

    /**
     * Get quantity6
     *
     * @return integer
     */
    public function getQuantity6()
    {
        return $this->quantity6;
    }

    /**
     * Set quantity7
     *
     * @param integer $quantity7
     * @return Craft
     */
    public function setQuantity7($quantity7)
    {
        $this->quantity7 = $quantity7;

        return $this;
    }

    /**
     * Get quantity7
     *
     * @return integer
     */
    public function getQuantity7()
    {
        return $this->quantity7;
    }

    /**
     * Set quantity8
     *
     * @param integer $quantity8
     * @return Craft
     */
    public function setQuantity8($quantity8)
    {
        $this->quantity8 = $quantity8;

        return $this;
    }

    /**
     * Get quantity8
     *
     * @return integer
     */
    public function getQuantity8()
    {
        return $this->quantity8;
    }

    public function isPersonalized() { return $this->getItemTemplate()->isPersonalized(); }
    public function isEquipment() { return $this->getItemTemplate()->isEquipment(); }
    public function isSkinned() { return $this->getItemTemplate()->isSkinned(); }
    public function isAnimal() { return $this->getItemTemplate()->isAnimal(); }
    public function isPet() { return $this->getItemTemplate()->isPet(); }
    public function isMount() { return $this->getItemTemplate()->isMount(); }
    public function isWeapon() { return $this->getItemTemplate()->isWeapon(); }
    public function isCraft() { return true; }
}
