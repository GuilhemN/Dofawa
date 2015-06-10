<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use Dof\Bundle\MapBundle\Entity\SubArea;

/**
 * AnimalTemplate.
 *
 * @ORM\Entity(repositoryClass="AnimalTemplateRepository")
 */
class AnimalTemplate extends EquipmentTemplate
{
    /**
     * @var int
     *
     * @ORM\Column(name="bone", type="integer", nullable=true, unique=false)
     */
    private $bone;

    /**
     * @var int
     *
     * @ORM\Column(name="colorization_type", type="integer", nullable=true)
     */
    private $colorizationType;

    /**
     * @var int
     *
     * @ORM\Column(name="favorite_area_bonus", type="integer")
     */
    private $favoriteAreaBonus;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Dof\Bundle\MapBundle\Entity\SubArea")
     * @ORM\JoinTable(name="dof_animal_favorite_areas")
     */
    private $favoriteAreas;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size;

    public function __construct()
    {
        parent::__construct();
        $this->favoriteAreas = new ArrayCollection();
    }

    /**
     * Set bone.
     *
     * @param int $bone
     *
     * @return AnimalTemplate
     */
    public function setBone($bone)
    {
        $this->bone = $bone;

        return $this;
    }

    /**
     * Get bone.
     *
     * @return int
     */
    public function getBone()
    {
        return $this->bone;
    }

    /**
     * Set colorizationType.
     *
     * @param int $colorizationType
     *
     * @return AnimalTemplate
     */
    public function setColorizationType($colorizationType)
    {
        $this->colorizationType = $colorizationType;

        return $this;
    }

    /**
     * Get colorizationType.
     *
     * @return int
     */
    public function getColorizationType()
    {
        return $this->colorizationType;
    }

    /**
     * Set favoriteAreaBonus.
     *
     * @param int $favoriteAreaBonus
     *
     * @return AnimalTemplate
     */
    public function setFavoriteAreaBonus($favoriteAreaBonus)
    {
        $this->favoriteAreaBonus = $favoriteAreaBonus;

        return $this;
    }

    /**
     * Get favoriteAreaBonus.
     *
     * @return int
     */
    public function getFavoriteAreaBonus()
    {
        return $this->favoriteAreaBonus;
    }

    /**
     * Set size.
     *
     * @param int $size
     *
     * @return AnimalTemplate
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Add favoriteAreas.
     *
     * @param SubArea $favoriteAreas
     *
     * @return AnimalTemplate
     */
    public function addFavoriteArea(SubArea $favoriteAreas)
    {
        $this->favoriteAreas[] = $favoriteAreas;

        return $this;
    }

    /**
     * Remove favoriteAreas.
     *
     * @param SubArea $favoriteAreas
     *
     * @return AnimalTemplate
     */
    public function removeFavoriteArea(SubArea $favoriteAreas)
    {
        $this->favoriteAreas->removeElement($favoriteAreas);

        return $this;
    }

    /**
     * Get favoriteAreas.
     *
     * @return Collection
     */
    public function getFavoriteAreas()
    {
        return $this->favoriteAreas;
    }

    public function isAnimal()
    {
        return true;
    }
    public function getClassId()
    {
        return 'animal';
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + ($full ? [
            'bone' => $this->bone,
            'colorizationType' => $this->colorizationType,
            'size' => $this->size,
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
