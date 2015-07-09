<?php

namespace Dof\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;

/**
 * Month.
 *
 * @ORM\Table(name="dof_months")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MainBundle\Entity\MonthRepository")
 */
class Month implements IdentifiableInterface
{
    use SluggableTrait, LocalizedNameTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Month
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

    public function __toString()
    {
        return $this->name;
    }
}
