<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * MonsterSuperRace.
 *
 * @ORM\Table(name="dof_monster_super_races")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MonsterBundle\Entity\MonsterSuperRaceRepository")
 */
class MonsterSuperRace implements IdentifiableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="MonsterRace", mappedBy="parent")
     * @ORM\JoinColumn(nullable=true)
     */
    private $children;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return MonsterSuperRace
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
     * Add children.
     *
     * @param MonsterRace $children
     *
     * @return MonsterSuperRace
     */
    public function addChild(MonsterRace $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children.
     *
     * @param MonsterRace $children
     *
     * @return MonsterSuperRace
     */
    public function removeChild(MonsterRace $children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * Get children.
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->name;
    }
}
