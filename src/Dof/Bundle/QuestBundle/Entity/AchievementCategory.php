<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * AchievementCategory.
 *
 * @ORM\Table(name="dof_achievement_categories")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\AchievementCategoryRepository")
 */
class AchievementCategory implements IdentifiableInterface, LocalizedNameInterface
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
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @var int
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var AchievementCategory
     *
     * @ORM\ManyToOne(targetEntity="AchievementCategory", inversedBy="child")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AchievementCategory", mappedBy="parent")
     * @ORM\JoinColumn(nullable=true)
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Achievement", mappedBy="category")
     * @ORM\JoinColumn(nullable=true)
     */
    private $achievements;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->achievements = new ArrayCollection();
    }

    /**
     * set id.
     *
     * @return Achievement
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
     * Set icon.
     *
     * @param string $icon
     *
     * @return AchievementCategory
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set order.
     *
     * @param int $order
     *
     * @return AchievementCategory
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
     * Set color.
     *
     * @param string $color
     *
     * @return AchievementCategory
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set parent.
     *
     * @param AchievementCategory $parent
     *
     * @return AchievementCategory
     */
    public function setParent(AchievementCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return AchievementCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children.
     *
     * @param AchievementCategory $children
     *
     * @return AchievementCategory
     */
    public function addChild(AchievementCategory $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children.
     *
     * @param AchievementCategory $children
     *
     * @return AchievementCategory
     */
    public function removeChild(AchievementCategory $children)
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

    /**
     * Add achievements.
     *
     * @param Achievement $achievements
     *
     * @return AchievementCategory
     */
    public function addAchievement(Achievement $achievements)
    {
        $this->achievements[] = $achievements;

        return $this;
    }

    /**
     * Remove achievements.
     *
     * @param Achievement $achievements
     *
     * @return AchievementCategory
     */
    public function removeAchievement(Achievement $achievements)
    {
        $this->achievements->removeElement($achievements);

        return $this;
    }

    /**
     * Get achievements.
     *
     * @return Collection
     */
    public function getAchievements()
    {
        return $this->achievements;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
