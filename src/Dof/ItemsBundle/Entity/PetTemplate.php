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
     * @ORM\JoinTable(name="dof_pet_food_items")
	 */
	private $foodItems;

    /**
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="ItemTemplate")
     * @ORM\JoinTable(name="dof_pet_eat_range")
     */
    private $minEatRange;

    /**
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="ItemTemplate")
     * @ORM\JoinTable(name="dof_pet_eat_range")
     */
    private $maxEatRange;

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
     * Set minEatRange
     *
     * @param integer $minEatRange
     * @return PetTemplate
     */
    public function setMinEatRange($minEatRange)
    {
        $this->minEatRange = $minEatRange;

        return $this;
    }

    /**
     * Get minEatRange
     *
     * @return integer
     */
    public function getMinEatRange()
    {
        return $this->minEatRange;
    }

    /**
     * Set maxEatRange
     *
     * @param integer $maxEatRange
     * @return PetTemplate
     */
    public function setMaxEatRange($maxEatRange)
    {
        $this->maxEatRange = $maxEatRange;

        return $this;
    }

    /**
     * Get maxEatRange
     *
     * @return integer
     */
    public function getMaxEatRange()
    {
        return $this->maxEatRange;
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
