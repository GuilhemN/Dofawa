<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\Element;

/**
 * WeaponDamageRow
 *
 * @ORM\Table(name="dof_weapon_damage_rows")
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\WeaponDamageRowRepository")
 */
class WeaponDamageRow
{
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="order", type="integer")
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="element", type="integer")
     */
    private $element;

    /**
     * @var integer
     *
     * @ORM\Column(name="min", type="integer")
     */
    private $min;

    /**
     * @var integer
     *
     * @ORM\Column(name="max", type="integer")
     */
    private $max;

    /**
     * @var boolean
     *
     * @ORM\Column(name="leech", type="boolean")
     */
    private $leech;

    /**
     * @var WeaponTemplate
     *
     * @ORM\ManyToOne(targetEntity="Dof\ItemsBundle\Entity\WeaponTemplate", inversedBy="damageRows")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $weapon;

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
     * Set order
     *
     * @param integer $order
     * @return WeaponDamageRow
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set element
     *
     * @param integer $element
     * @return WeaponDamageRow
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return integer 
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set min
     *
     * @param integer $min
     * @return WeaponDamageRow
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return integer 
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param integer $max
     * @return WeaponDamageRow
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return integer 
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set leech
     *
     * @param boolean $leech
     * @return WeaponDamageRow
     */
    public function setLeech($leech)
    {
        $this->leech = $leech;

        return $this;
    }

    /**
     * Get leech
     *
     * @return boolean 
     */
    public function getLeech()
    {
        return $this->leech;
    }

    /**
     * Get leech
     *
     * @return boolean 
     */
    public function isLeech()
    {
        return $this->leech;
    }

    /**
     * Set weapon
     *
     * @param WeaponTemplate $weapon
     * @return WeaponDamageRow
     */
    public function setWeapon(WeaponTemplate $weapon = null)
    {
        $this->weapon = $weapon;

        return $this;
    }

    /**
     * Get weapon
     *
     * @return WeaponTemplate 
     */
    public function getWeapon()
    {
        return $this->weapon;
    }

    public function canMage()
    {
        return $this->element == Element::NEUTRAL && !$this->leech;
    }
}