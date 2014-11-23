<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * Spell
 *
 * @ORM\Table(name="dof_spells")
 * @ORM\Entity(repositoryClass="SpellRepository")
 */
class Spell implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, LocalizedDescriptionTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="iconId", type="integer")
     */
    private $iconId;

    /**
     * @var integer
     *
     * @ORM\Column(name="typeId", type="integer")
     */
    private $typeId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publiclyVisible", type="boolean")
     */
    private $publiclyVisible;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="SpellRank", mappedBy="spell")
     * @ORM\OrderBy({ "rank" = "ASC" })
     */
    private $ranks;

    public function __construct()
    {
        $this->ranks = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Spell
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set iconId
     *
     * @param integer $iconId
     * @return Spell
     */
    public function setIconId($iconId)
    {
        $this->iconId = $iconId;

        return $this;
    }

    /**
     * Get iconId
     *
     * @return integer
     */
    public function getIconId()
    {
        return $this->iconId;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Spell
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set publiclyVisible
     *
     * @param boolean $publiclyVisible
     * @return Spell
     */
    public function setPubliclyVisible($publiclyVisible)
    {
        $this->publiclyVisible = $publiclyVisible;

        return $this;
    }

    /**
     * Get publiclyVisible
     *
     * @return boolean
     */
    public function getPubliclyVisible()
    {
        return $this->publiclyVisible;
    }

    /**
     * Get publiclyVisible
     *
     * @return boolean
     */
    public function isPubliclyVisible()
    {
        return $this->publiclyVisible;
    }

    /**
     * Add ranks
     *
     * @param SpellRank $ranks
     * @return Spell
     */
    public function addRank(SpellRank $ranks)
    {
        $this->ranks[] = $ranks;

        return $this;
    }

    /**
     * Remove ranks
     *
     * @param SpellRank $ranks
     * @return Spell
     */
    public function removeRank(SpellRank $ranks)
    {
        $this->ranks->removeElement($ranks);

        return $this;
    }

    /**
     * Get ranks
     *
     * @return Collection
     */
    public function getRanks()
    {
        return $this->ranks;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
