<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * QuestCategory.
 *
 * @ORM\Table(name="dof_quest_categories")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\QuestCategoryRepository")
 */
class QuestCategory implements IdentifiableInterface, LocalizedNameInterface
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
     * @var int
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * @ORM\OneToMany(targetEntity="Quest", mappedBy="category")
     * @ORM\JoinColumn(nullable=true)
     */
    private $quests;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @return QuestCategory
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
     * Set order.
     *
     * @param int $order
     *
     * @return QuestCategory
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Add quests.
     *
     * @param Quest $quests
     *
     * @return QuestCategory
     */
    public function addQuest(Quest $quests)
    {
        $this->quests[] = $quests;

        return $this;
    }

    /**
     * Remove quests.
     *
     * @param Quest $quests
     *
     * @return QuestCategory
     */
    public function removeQuest(Quest $quests)
    {
        $this->quests->removeElement($quests);

        return $this;
    }

    /**
     * Get quests.
     *
     * @return Collection
     */
    public function getQuests()
    {
        return $this->quests;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
