<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

use Dof\CharactersBundle\CastableTrait;

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
     * @ORM\OrderBy({ "order" = "ASC", "id" = "ASC" })
	 */
	private $damageRows;

    /**
     * @var boolean
     *
     * @ORM\Column(name="two_handed", type="boolean")
     */
    private $twoHanded;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ethereal", type="boolean")
     */
    private $ethereal;

    /**
     * @var integer
     *
     * @ORM\Column(name="critical_hit_bonus", type="integer")
     */
    private $criticalHitBonus;

	use CastableTrait;

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

    /**
     * Set twoHanded
     *
     * @param boolean $twoHanded
     * @return WeaponTemplate
     */
    public function setTwoHanded($twoHanded)
    {
        $this->twoHanded = $twoHanded;

        return $this;
    }

    /**
     * Get twoHanded
     *
     * @return boolean
     */
    public function getTwoHanded()
    {
        return $this->twoHanded;
    }

    /**
     * Get twoHanded
     *
     * @return boolean
     */
    public function isTwoHanded()
    {
        return $this->twoHanded;
    }

    /**
     * Set ethereal
     *
     * @param boolean $ethereal
     * @return WeaponTemplate
     */
    public function setEthereal($ethereal)
    {
        $this->ethereal = $ethereal;

        return $this;
    }

    /**
     * Get ethereal
     *
     * @return boolean
     */
    public function getEthereal()
    {
        return $this->ethereal;
    }

    /**
     * Get ethereal
     *
     * @return boolean
     */
    public function isEthereal()
    {
        return $this->ethereal;
    }

    /**
     * Set criticalHitBonus
     *
     * @param integer $criticalHitBonus
     * @return WeaponTemplate
     */
    public function setCriticalHitBonus($criticalHitBonus)
    {
        $this->criticalHitBonus = $criticalHitBonus;

        return $this;
    }

    /**
     * Get criticalHitBonus
     *
     * @return integer
     */
    public function getCriticalHitBonus()
    {
        return $this->criticalHitBonus;
    }

    public function canMage()
    {
        if (!$this->isEnhanceable())
            return false;
        foreach ($this->damageRows as $row)
            if ($row->canMage())
                return true;
        return false;
    }

	public function isWeapon() { return true; }
	public function getClassId() { return 'weapon'; }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + $this->exportCastableData($full) + ($full ? [
            'damageRows' => array_map(function ($ent) use ($locale) { return $ent->exportData(false, $locale); }, $this->damageRows->toArray()),
            'twoHanded' => $this->twoHanded,
            'ethereal' => $this->ethereal,
            'criticalHitBonus' => $this->criticalHitBonus
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale))
            return true;
        return false;
    }
}
