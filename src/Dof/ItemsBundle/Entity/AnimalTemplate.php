<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use Dof\MapBundle\Entity\SubArea;

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
     * @ORM\Column(name="bone", type="integer", nullable=true, unique=false)
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

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Dof\MapBundle\Entity\SubArea")
     * @ORM\JoinTable(name="dof_animal_favorite_areas")
	 */
	private $favoriteAreas;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

	public function __construct()
	{
		parent::__construct();
        $this->favoriteAreas = new ArrayCollection();
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

    /**
     * Add favoriteAreas
     *
     * @param SubArea $favoriteAreas
     * @return AnimalTemplate
     */
    public function addFavoriteArea(SubArea $favoriteAreas)
    {
        $this->favoriteAreas[] = $favoriteAreas;

        return $this;
    }

    /**
     * Remove favoriteAreas
     *
     * @param SubArea $favoriteAreas
     * @return AnimalTemplate
     */
    public function removeFavoriteArea(SubArea $favoriteAreas)
    {
        $this->favoriteAreas->removeElement($favoriteAreas);

        return $this;
    }

    /**
     * Get favoriteAreas
     *
     * @return Collection
     */
    public function getFavoriteAreas()
    {
        return $this->favoriteAreas;
    }

	public function isAnimal() { return true; }
}
