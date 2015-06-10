<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use Dof\Bundle\CharacterBundle\CastableTrait;

/**
 * WeaponTemplate.
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\ItemBundle\Entity\WeaponTemplateRepository")
 */
class WeaponTemplate extends SkinnedEquipmentTemplate
{
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\Bundle\ItemBundle\Entity\WeaponDamageRow", mappedBy="weapon")
     * @ORM\OrderBy({ "order" = "ASC", "id" = "ASC" })
     */
    private $damageRows;

    /**
     * @var bool
     *
     * @ORM\Column(name="two_handed", type="boolean")
     */
    private $twoHanded;

    /**
     * @var bool
     *
     * @ORM\Column(name="ethereal", type="boolean")
     */
    private $ethereal;

    /**
     * @var int
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
     * Add damageRows.
     *
     * @param WeaponDamageRow $damageRow
     *
     * @return WeaponTemplate
     */
    public function addDamageRow(WeaponDamageRow $damageRow)
    {
        $this->damageRows[] = $damageRow;

        return $this;
    }

    /**
     * Remove damageRows.
     *
     * @param WeaponDamageRow $damageRow
     *
     * @return WeaponTemplate
     */
    public function removeDamageRow(WeaponDamageRow $damageRow)
    {
        $this->damageRows->removeElement($damageRow);

        return $this;
    }

    /**
     * Get damageRows.
     *
     * @return Collection
     */
    public function getDamageRows()
    {
        return $this->damageRows;
    }

    /**
     * Set twoHanded.
     *
     * @param bool $twoHanded
     *
     * @return WeaponTemplate
     */
    public function setTwoHanded($twoHanded)
    {
        $this->twoHanded = $twoHanded;

        return $this;
    }

    /**
     * Get twoHanded.
     *
     * @return bool
     */
    public function getTwoHanded()
    {
        return $this->twoHanded;
    }

    /**
     * Get twoHanded.
     *
     * @return bool
     */
    public function isTwoHanded()
    {
        return $this->twoHanded;
    }

    /**
     * Set ethereal.
     *
     * @param bool $ethereal
     *
     * @return WeaponTemplate
     */
    public function setEthereal($ethereal)
    {
        $this->ethereal = $ethereal;

        return $this;
    }

    /**
     * Get ethereal.
     *
     * @return bool
     */
    public function getEthereal()
    {
        return $this->ethereal;
    }

    /**
     * Get ethereal.
     *
     * @return bool
     */
    public function isEthereal()
    {
        return $this->ethereal;
    }

    /**
     * Set criticalHitBonus.
     *
     * @param int $criticalHitBonus
     *
     * @return WeaponTemplate
     */
    public function setCriticalHitBonus($criticalHitBonus)
    {
        $this->criticalHitBonus = $criticalHitBonus;

        return $this;
    }

    /**
     * Get criticalHitBonus.
     *
     * @return int
     */
    public function getCriticalHitBonus()
    {
        return $this->criticalHitBonus;
    }

    public function getDamageEntries()
    {
        $ents = [];
        foreach ($this->damageRows as $row) {
            $ent = $row->getDamageEntry();
            if ($ent) {
                $ents[] = $ent;
            }
        }

        return $ents;
    }

    public function getCriticalDamageEntries()
    {
        if (!$this->getCriticalHitDenominator()) {
            return $this->getDamageEntries();
        }
        $ents = [];
        $chb = $this->criticalHitBonus;
        foreach ($this->damageRows as $row) {
            $ent = $row->getDamageEntry();
            if ($ent) {
                $ent['min'] += $chb;
                $ent['max'] += $chb;
                $ents[] = $ent;
            }
        }

        return $ents;
    }

    public function canMage()
    {
        if (!$this->isEnhanceable()) {
            return false;
        }
        foreach ($this->damageRows as $row) {
            if ($row->canMage()) {
                return true;
            }
        }

        return false;
    }

    public function isWeapon()
    {
        return true;
    }
    public function getClassId()
    {
        return 'weapon';
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + $this->exportCastableData($full) + ($full ? [
            'damageRows' => array_map(function ($ent) use ($locale) { return $ent->exportData(false, $locale); }, $this->damageRows->toArray()),
            'twoHanded' => $this->twoHanded,
            'ethereal' => $this->ethereal,
            'criticalHitBonus' => $this->criticalHitBonus,
        ] : []);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale)) {
            return true;
        }

        return false;
    }
}
