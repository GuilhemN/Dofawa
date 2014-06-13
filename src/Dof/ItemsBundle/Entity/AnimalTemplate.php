<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnimalTemplate
 *
 * @ORM\Entity(repositoryClass="AnimalTemplateRepository")
 */
class AnimalTemplate extends EquipmentTemplate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="bone", type="integer", nullable=true)
     */
    private $bone;

    /**
     * @var integer
     *
     * @ORM\Column(name="colorization_type", type="integer")
     */
    private $colorizationType;

    /**
     * @var integer
     *
     * @ORM\Column(name="favorite_area_bonus", type="integer")
     */
    private $favoriteAreaBonus;

	/* (no /** because attr is disabled, because only partly defined for now)
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="**TBD**")
	 */
	//private $favoriteAreas;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Set bone
     *
     * @param integer $bone
     * @return AnimalTemplate
     */
    public function setBone($bone)
    {
        $this->bone = $bone;

        return $this;
    }

    /**
     * Get bone
     *
     * @return integer
     */
    public function getBone()
    {
        return $this->bone;
    }

    /**
     * Set colorizationType
     *
     * @param integer $colorizationType
     * @return AnimalTemplate
     */
    public function setColorizationType($colorizationType)
    {
        $this->colorizationType = $colorizationType;

        return $this;
    }

    /**
     * Get colorizationType
     *
     * @return integer
     */
    public function getColorizationType()
    {
        return $this->colorizationType;
    }
    
    /**
     * Set favoriteAreaBonus
     *
     * @param integer $favoriteAreaBonus
     * @return AnimalTemplate
     */
    public function setFavoriteAreaBonus($favoriteAreaBonus)
    {
        $this->favoriteAreaBonus = $favoriteAreaBonus;

        return $this;
    }

    /**
     * Get favoriteAreaBonus
     *
     * @return integer
     */
    public function getFavoriteAreaBonus()
    {
        return $this->favoriteAreaBonus;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return AnimalTemplate
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

	public function isAnimal() { return true; }
}