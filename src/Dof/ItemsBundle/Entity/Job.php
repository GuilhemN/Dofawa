<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;

use XN\DataBundle\LocalizedNameTrait;

/**
 * Job
 *
 * @ORM\Table(name="dof_jobs")
 * @ORM\Entity(repositoryClass="JobRepository")
 */
class Job implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    /**
     * @var integer
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

    public function __construct()
    {
        $this->gatherableItems = new ArrayCollection();
        $this->craftableItems = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Job
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
     * Add gatherableItems
     *
     * @param ItemTemplate $gatherableItems
     * @return Job
     */
    public function addGatherableItem(ItemTemplate $gatherableItems)
    {
        $this->gatherableItems[] = $gatherableItems;

        return $this;
    }

    /**
     * Remove gatherableItems
     *
     * @param ItemTemplate $gatherableItems
     * @return Job
     */
    public function removeGatherableItem(ItemTemplate $gatherableItems)
    {
        $this->gatherableItems->removeElement($gatherableItems);

        return $this;
    }

    /**
     * Get gatherableItems
     *
     * @return Collection
     */
    public function getGatherableItems()
    {
        return $this->gatherableItems;
    }

    /**
     * Add craftableItems
     *
     * @param ItemTemplate $craftableItems
     * @return Job
     */
    public function addCraftableItem(ItemTemplate $craftableItems)
    {
        $this->craftableItems[] = $craftableItems;

        return $this;
    }

    /**
     * Remove craftableItems
     *
     * @param ItemTemplate $craftableItems
     * @return Job
     */
    public function removeCraftableItem(ItemTemplate $craftableItems)
    {
        $this->craftableItems->removeElement($craftableItems);

        return $this;
    }

    /**
     * Get craftableItems
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
