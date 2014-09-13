<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\ReverseSetter;
use Dof\ItemsBundle\NoteHelper;

use Dof\ItemsBundle\CharacteristicsRangeTrait;
use Dof\ItemsBundle\PrimaryBonusInterface;
use Dof\ItemsBundle\PrimaryBonusTrait;

use Dof\BuildBundle\Entity\Item;

/**
 * EquipmentTemplate
 *
 * @ORM\Entity(repositoryClass="EquipmentTemplateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EquipmentTemplate extends ItemTemplate implements PrimaryBonusInterface
{
	use CharacteristicsRangeTrait, PrimaryBonusTrait;

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

	private $originalSets;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Item", mappedBy="itemTemplate")
     */
    private $buildItems;

    /**
     * @var boolean
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
		ReverseSetter::reverseCall($this->set, 'removeItem', $this);
		$this->set = $set;
		ReverseSetter::reverseCall($set, 'addItem', $this);

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

	public function getOriginalSets(){
		$return = (array) $this->originalSets;
		if($this->set instanceof ItemSet)
			$return = array_merge($return, [$this->set->getId() => $this->set]);

		return $return;
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

	public function getPowerRate(){
		return $this->powerRate;
	}

	public function setPowerRate($powerRate){
		$this->powerRate = $powerRate;
		return $this;
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

    public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array()){

        $biggestCombination = null;

        foreach($primaryFields as $k => $v)
            $caracts[$v['primaryBonus']] += ($this->{'getMax' . ucfirst($k)}() + $this->{'getMin' . ucfirst($k)}()) / 2 * $v['weight'];

        return $caracts;
    }

    public function getCascadeForPrimaryBonus(){
        return $this->set;
    }
}
