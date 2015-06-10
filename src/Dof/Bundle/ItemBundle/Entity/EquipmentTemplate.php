<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use Dof\Bundle\ItemBundle\CharacteristicsRangeTrait;
use Dof\Bundle\ItemBundle\PrimaryBonusInterface;
use Dof\Bundle\ItemBundle\PrimaryBonusTrait;

/**
 * EquipmentTemplate.
 *
 * @ORM\Entity(repositoryClass="EquipmentTemplateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EquipmentTemplate extends ItemTemplate implements PrimaryBonusInterface
{
    use CharacteristicsRangeTrait, PrimaryBonusTrait;

    /**
     * @var bool
     *
     * @ORM\Column(name="enhanceable", type="boolean")
     */
    private $enhanceable;

    /**
     * @var ItemSet
     *
     * @ORM\ManyToOne(targetEntity="ItemSet", inversedBy="items")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $set;

    /**
     * @var int
     *
     * @ORM\Column(name="power_rate", type="integer")
     */
    private $powerRate;

    public function __construct()
    {
        $this->originalSets = array();
        parent::__construct();
    }

    /**
     * Set enhanceable.
     *
     * @param bool $enhanceable
     *
     * @return EquipmentTemplate
     */
    public function setEnhanceable($enhanceable)
    {
        $this->enhanceable = $enhanceable;

        return $this;
    }

    /**
     * Get enhanceable.
     *
     * @return bool
     */
    public function getEnhanceable()
    {
        return $this->enhanceable;
    }

    /**
     * Get enhanceable.
     *
     * @return bool
     */
    public function isEnhanceable()
    {
        return $this->enhanceable;
    }

    /**
     * Set set.
     *
     * @param ItemSet $set
     *
     * @return EquipmentTemplate
     */
    public function setSet(ItemSet $set = null)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Get set.
     *
     * @return ItemSet
     */
    public function getSet()
    {
        return $this->set;
    }

    public function getPowerRate()
    {
        return $this->powerRate;
    }

    public function setPowerRate($powerRate)
    {
        $this->powerRate = $powerRate;

        return $this;
    }

    public function isEquipment()
    {
        return true;
    }
    public function getClassId()
    {
        return 'equip';
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + ($full ? [
            'enhanceable' => $this->enhanceable,
            'set' => ($this->set === null) ? null : $this->set->exportData(false, $locale),
            'characteristics' => $this->getCharacteristics(),
        ] : []);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale)) {
            return true;
        }

        return false;
    }

    public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array())
    {
        $biggestCombination = null;

        foreach ($primaryFields as $k => $v) {
            if (!isset($caracts[$v['primaryBonus']])) {
                $caracts[$v['primaryBonus']] = 0;
            }

            $caracts[$v['primaryBonus']] += ($this->{'getMax'.ucfirst($k)}() + $this->{'getMin'.ucfirst($k)}()) / 2 * $v['weight'];
        }

        return $caracts;
    }

    public function getCascadeForPrimaryBonus()
    {
        return $this->set;
    }
}
