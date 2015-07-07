<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;

/**
 * Job.
 *
 * @ORM\Table(name="dof_jobs")
 * @ORM\Entity(repositoryClass="JobRepository")
 */
class Job implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, LocalizedNameTrait;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemTemplate", mappedBy="gatheringJob")
     */
    private $gatherableItems;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemTemplate", mappedBy="craftingJob")
     */
    private $craftableItems;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->gatherableItems = new ArrayCollection();
        $this->craftableItems = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Job
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
     * Add gatherableItems.
     *
     * @param ItemTemplate $gatherableItems
     *
     * @return Job
     */
    public function addGatherableItem(ItemTemplate $gatherableItems)
    {
        $this->gatherableItems[] = $gatherableItems;

        return $this;
    }

    /**
     * Remove gatherableItems.
     *
     * @param ItemTemplate $gatherableItems
     *
     * @return Job
     */
    public function removeGatherableItem(ItemTemplate $gatherableItems)
    {
        $this->gatherableItems->removeElement($gatherableItems);

        return $this;
    }

    /**
     * Get gatherableItems.
     *
     * @return Collection
     */
    public function getGatherableItems()
    {
        return $this->gatherableItems;
    }

    /**
     * Add craftableItems.
     *
     * @param ItemTemplate $craftableItems
     *
     * @return Job
     */
    public function addCraftableItem(ItemTemplate $craftableItems)
    {
        $this->craftableItems[] = $craftableItems;

        return $this;
    }

    /**
     * Remove craftableItems.
     *
     * @param ItemTemplate $craftableItems
     *
     * @return Job
     */
    public function removeCraftableItem(ItemTemplate $craftableItems)
    {
        $this->craftableItems->removeElement($craftableItems);

        return $this;
    }

    /**
     * Get craftableItems.
     *
     * @return Collection
     */
    public function getCraftableItems()
    {
        return $this->craftableItems;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
