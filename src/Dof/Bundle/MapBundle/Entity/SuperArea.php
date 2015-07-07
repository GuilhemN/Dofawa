<?php

namespace Dof\Bundle\MapBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * SuperArea.
 *
 * @ORM\Table(name="dof_map_superareas")
 * @ORM\Entity(repositoryClass="SuperAreaRepository")
 */
class SuperArea implements IdentifiableInterface, LocalizedNameInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ReleaseBoundTrait, LocalizedNameTrait;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Area", mappedBy="superArea")
     */
    private $areas;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->areas = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return SuperArea
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
     * Add areas.
     *
     * @param Area $areas
     *
     * @return SuperArea
     */
    public function addArea(Area $areas)
    {
        $this->areas[] = $areas;

        return $this;
    }

    /**
     * Remove areas.
     *
     * @param Area $areas
     *
     * @return SuperArea
     */
    public function removeArea(Area $areas)
    {
        $this->areas->removeElement($areas);

        return $this;
    }

    /**
     * Get areas.
     *
     * @return Collection
     */
    public function getAreas()
    {
        return $this->areas;
    }

    public function __toString()
    {
        return $this->name;
    }
}
