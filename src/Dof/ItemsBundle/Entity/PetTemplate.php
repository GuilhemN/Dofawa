<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

/**
 * PetTemplate
 *
 * @ORM\Entity(repositoryClass="PetTemplateRepository")
 */
class PetTemplate extends AnimalTemplate
{
	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="ItemType")
     * @ORM\JoinTable(name="dof_pet_food_types")
	 */
	private $foodTypes;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="ItemTemplate")
     * @ORM\JoinTable(name="dof_pet_feed_items")
	 */
	private $foodItems;

    /**
     * @var integer
     *
	 * @ORM\Column(name="min_feed_interval", type="integer")
     */
    private $minFeedInterval;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_feed_interval", type="integer")
     */
    private $maxFeedInterval;

	public function __construct()
	{
		parent::__construct();
		$this->foodTypes = new ArrayCollection();
		$this->foodItems = new ArrayCollection();
	}

    /**
     * Add foodTypes
     *
     * @param ItemType $foodTypes
     * @return PetTemplate
     */
    public function addFoodType(ItemType $foodTypes)
    {
        $this->foodTypes[] = $foodTypes;

        return $this;
    }

    /**
     * Remove foodTypes
     *
     * @param ItemType $foodTypes
     * @return PetTemplate
     */
    public function removeFoodType(ItemType $foodTypes)
    {
        $this->foodTypes->removeElement($foodTypes);

        return $this;
    }

    /**
     * Get foodTypes
     *
     * @return Collection
     */
    public function getFoodTypes()
    {
        return $this->foodTypes;
    }

    /**
     * Add foodItems
     *
     * @param ItemTemplate $foodItems
     * @return PetTemplate
     */
    public function addFoodItem(ItemTemplate $foodItems)
    {
        $this->foodItems[] = $foodItems;

        return $this;
    }

    /**
     * Remove foodItems
     *
     * @param ItemTemplate $foodItems
     * @return PetTemplate
     */
    public function removeFoodItem(ItemTemplate $foodItems)
    {
        $this->foodItems->removeElement($foodItems);

        return $this;
    }

    /**
     * Get foodItems
     *
     * @return Collection
     */
    public function getFoodItems()
    {
        return $this->foodItems;
    }

    /**
     * Set minFeedInterval
     *
     * @param integer $minFeedInterval
     * @return PetTemplate
     */
    public function setMinFeedInterval($minFeedInterval)
    {
        $this->minFeedInterval = $minFeedInterval;

        return $this;
    }

    /**
     * Get minFeedInterval
     *
     * @return integer
     */
    public function getMinFeedInterval()
    {
        return $this->minFeedInterval;
    }

    /**
     * Set maxFeedInterval
     *
     * @param integer $maxFeedInterval
     * @return PetTemplate
     */
    public function setMaxFeedInterval($maxFeedInterval)
    {
        $this->maxFeedInterval = $maxFeedInterval;

        return $this;
    }

    /**
     * Get maxFeedInterval
     *
     * @return integer
     */
    public function getMaxFeedInterval()
    {
        return $this->maxFeedInterval;
    }

	public function isPet() { return true; }
	public function getClassId() { return 'pet'; }



    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale))
            return true;
        return false;
    }
}
