<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * Title.
 *
 * @ORM\Table(name="dof_titles")
 * @ORM\Entity(repositoryClass="Dof\Bundle\CharacterBundle\Entity\TitleRepository")
 */
class Title implements IdentifiableInterface
{
    use TimestampableTrait, SluggableTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var int
     *
     * @ORM\Column(name="categoryId", type="integer")
     */
    private $categoryId;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name_male", type="string", length=255, nullable=true)
     */
    private $nameMale;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name_female", type="string", length=255, nullable=true)
     */
    private $nameFemale;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Emoticon
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set visible.
     *
     * @param bool $visible
     *
     * @return Title
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set categoryId.
     *
     * @param int $categoryId
     *
     * @return Title
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId.
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set nameMale.
     *
     * @param string $nameMale
     *
     * @return Emoticon
     */
    public function setNameMale($nameMale)
    {
        $this->nameMale = $nameMale;

        return $this;
    }

    /**
     * Get nameMaleName.
     *
     * @return string
     */
    public function getNameMale()
    {
        return $this->nameMaleName;
    }

    /**
     * Set nameFemale.
     *
     * @param string $nameFemale
     *
     * @return Emoticon
     */
    public function setNameFemale($nameFemale)
    {
        $this->nameFemale = $nameFemale;

        return $this;
    }

    /**
     * Get nameFemale.
     *
     * @return string
     */
    public function getNameFemale()
    {
        return $this->nameFemale;
    }

    public function __toString()
    {
        return $this->nameMale;
    }
}
