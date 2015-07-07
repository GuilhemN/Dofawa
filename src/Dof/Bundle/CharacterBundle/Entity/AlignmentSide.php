<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * AlignmentSide.
 *
 * @ORM\Table(name="dof_alignment_sides")
 * @ORM\Entity(repositoryClass="Dof\Bundle\CharacterBundle\Entity\AlignmentSideRepository")
 */
class AlignmentSide implements IdentifiableInterface, LocalizedNameInterface
{
    use SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

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
     * @ORM\Column(name="canConquest", type="boolean")
     */
    private $canConquest;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    /**
     * Get id.
     *
     * @param id $id
     *
     * @return AlignmentSide
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
     * Set canConquest.
     *
     * @param bool $canConquest
     *
     * @return AlignmentSide
     */
    public function setCanConquest($canConquest)
    {
        $this->canConquest = $canConquest;

        return $this;
    }

    /**
     * Get canConquest.
     *
     * @return bool
     */
    public function getCanConquest()
    {
        return $this->canConquest;
    }

    public function __toString()
    {
        return $this->name;
    }
}
