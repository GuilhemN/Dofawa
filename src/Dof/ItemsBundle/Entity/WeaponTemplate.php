<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * WeaponTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\WeaponTemplateRepository")
 */
class WeaponTemplate extends SkinnedEquipmentTemplate
{
	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Dof\ItemsBundle\Entity\WeaponDamageRow", mappedBy="weapon")
	 * @ORM\OrderBy({ "order" = "ASC" })
	 */
	private $damageRows;

	public function __construct()
	{
		parent::__construct();
		$this->damageRows = new ArrayCollection();
	}

    /**
     * Add damageRows
     *
     * @param WeaponDamageRow $damageRow
     * @return WeaponTemplate
     */
    public function addDamageRow(WeaponDamageRow $damageRow)
    {
    	$this->damageRows[] = $damageRow;
    	return $this;
    }

    /**
     * Remove damageRows
     *
     * @param WeaponDamageRow $damageRow
     * @return WeaponTemplate
     */
    public function removeDamageRow(WeaponDamageRow $damageRow)
    {
    	$this->damageRows->removeElement($damageRow);
    	return $this;
    }

    /**
     * Get damageRows
     *
     * @return Collection
     */
    public function getDamageRows()
    {
    	return $this->damageRows;
    }

	public function isWeapon() { return true; }

    public function canMage()
    {
        foreach ($this->damageRows as $row)
            if ($row->canMage())
                return true;
        return false;
    }
}