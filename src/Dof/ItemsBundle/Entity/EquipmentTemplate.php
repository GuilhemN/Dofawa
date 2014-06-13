<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\CharacteristicsRangeTrait;

/**
 * EquipmentTemplate
 *
 * @ORM\Entity(repositoryClass="EquipmentTemplateRepository")
 */
class EquipmentTemplate extends ItemTemplate
{
	use CharacteristicsRangeTrait;

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

	public function isEquipment() { return true; }
}