<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Dof\Bundle\ItemBundle\CharacteristicsRangeTrait;

/**
 * EquipmentTemplate.
 *
 * @ORM\Entity(repositoryClass="EquipmentTemplateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EquipmentTemplate extends ItemTemplate
{
    use CharacteristicsRangeTrait;

    /**
     * @var bool
     *
     * @ORM\Column(name="enhanceable", type="boolean")
     */
    private $enhanceable;

    /**
     * @var ItemSet
     *
     * @Groups({"item"})
     * @ORM\ManyToOne(targetEntity="ItemSet", inversedBy="items")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $set;

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
}
