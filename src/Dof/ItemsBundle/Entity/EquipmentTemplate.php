<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\CharacteristicsRangeTrait;
use Dof\ItemsBundle\ElementableInterface;
use Dof\ItemsBundle\ElementableTrait;

use Dof\BuildBundle\Entity\Item;

/**
 * EquipmentTemplate
 *
 * @ORM\Entity(repositoryClass="EquipmentTemplateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EquipmentTemplate extends ItemTemplate implements ElementableInterface
{
	use CharacteristicsRangeTrait, ElementableTrait;

    /**
     * @var boolean
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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Item", mappedBy="itemTemplate")
     */
    private $buildItems;

	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Set enhanceable
     *
     * @param boolean $enhanceable
     * @return EquipmentTemplate
     */
    public function setEnhanceable($enhanceable)
    {
        $this->enhanceable = $enhanceable;

        return $this;
    }

    /**
     * Get enhanceable
     *
     * @return boolean
     */
    public function getEnhanceable()
    {
        return $this->enhanceable;
    }

    /**
     * Get enhanceable
     *
     * @return boolean
     */
    public function isEnhanceable()
    {
        return $this->enhanceable;
    }

    /**
     * Set set
     *
     * @param ItemSet $set
     * @return EquipmentTemplate
     */
    public function setSet(ItemSet $set = null)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Get set
     *
     * @return ItemSet
     */
    public function getSet()
    {
        return $this->set;
    }

     /**
     * Add buildItem
     *
     * @param Item $buildItem
     * @return ItemTemplate
     */
    public function addBuildItem(Item $buildItem)
    {
        $this->buildItem[] = $buildItem;

        return $this;
    }

    /**
     * Remove buildItem
     *
     * @param Item $buildItem
     * @return ItemTemplate
     */
    public function removeBuildItem(Item $buildItem)
    {
        $this->buildItem->removeElement($buildItem);

        return $this;
    }

    /**
     * Get buildItem
     *
     * @return Collection
     */
    public function getBuildItem()
    {
        return $this->buildItem;
    }

	public function isEquipment() { return true; }
	public function getClassId() { return 'equip'; }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + ($full ? [
            'enhanceable' => $this->enhanceable,
            'set' => ($this->set === null) ? null : $this->set->exportData(false, $locale),
            'characteristics' => $this->getCharacteristics()
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale))
            return true;
        return false;
    }

    public function getCharacteristicsForElements($metadata, array $caracts = array()){

        $biggestCombination = null;

        foreach($metadata as $k => $v){
            $caracts[$v['element']] += ($this->{'getMax' . ucfirst($k)}() + $this->{'getMin' . ucfirst($k)}()) / 2 * $v['weight'];
        }

        return $caracts;
    }

    public function getParentElements(){
        return $this->set;
    }
}
