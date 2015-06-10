<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\ObjectManager;
use XN\Rest\ExportableInterface;
use XN\Rest\ImportableTrait;
use XN\Persistence\IdentifiableInterface;
use Dof\Bundle\ItemBundle\Element;

/**
 * WeaponDamageRow.
 *
 * @ORM\Table(name="dof_weapon_damage_rows")
 * @ORM\Entity(repositoryClass="WeaponDamageRowRepository")
 */
class WeaponDamageRow implements IdentifiableInterface, ExportableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use ImportableTrait;

    /**
     * @var WeaponTemplate
     *
     * @ORM\ManyToOne(targetEntity="WeaponTemplate", inversedBy="damageRows")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $weapon;

    /**
     * @var int
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * @var int
     *
     * @ORM\Column(name="element", type="integer")
     */
    private $element;

    /**
     * @var int
     *
     * @ORM\Column(name="min", type="integer")
     */
    private $min;

    /**
     * @var int
     *
     * @ORM\Column(name="max", type="integer")
     */
    private $max;

    /**
     * @var bool
     *
     * @ORM\Column(name="leech", type="boolean")
     */
    private $leech;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set weapon.
     *
     * @param WeaponTemplate $weapon
     *
     * @return WeaponDamageRow
     */
    public function setWeapon(WeaponTemplate $weapon)
    {
        $this->weapon = $weapon;

        return $this;
    }

    /**
     * Get weapon.
     *
     * @return WeaponTemplate
     */
    public function getWeapon()
    {
        return $this->weapon;
    }

    /**
     * Set order.
     *
     * @param int $order
     *
     * @return WeaponDamageRow
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set element.
     *
     * @param int $element
     *
     * @return WeaponDamageRow
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element.
     *
     * @return int
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set min.
     *
     * @param int $min
     *
     * @return WeaponDamageRow
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min.
     *
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max.
     *
     * @param int $max
     *
     * @return WeaponDamageRow
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max.
     *
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set leech.
     *
     * @param bool $leech
     *
     * @return WeaponDamageRow
     */
    public function setLeech($leech)
    {
        $this->leech = $leech;

        return $this;
    }

    /**
     * Get leech.
     *
     * @return bool
     */
    public function getLeech()
    {
        return $this->leech;
    }

    /**
     * Get leech.
     *
     * @return bool
     */
    public function isLeech()
    {
        return $this->leech;
    }

    public function canMage()
    {
        return $this->element == Element::NEUTRAL && !$this->leech;
    }

    public function getDamageEntry()
    {
        if ($this->element == Element::AP_LOSS) {
            return;
        }

        return [
            'element' => $this->element,
            'min' => $this->min,
            'max' => $this->max,
            'leech' => $this->leech,
        ];
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return [
            'element' => $this->element,
            'min' => $this->min,
            'max' => $this->max,
            'leech' => $this->leech,
        ] + ($full ? [
            'weapon' => $this->weapon->exportData(false, $locale),
            'order' => $this->order,
        ] : []);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        return false;
    }
}
