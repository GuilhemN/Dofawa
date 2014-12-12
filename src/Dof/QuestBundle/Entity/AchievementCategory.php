<?php

namespace Dof\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * AchievementCategory
 *
 * @ORM\Table(name="dof_achievement_categories")
 * @ORM\Entity(repositoryClass="Dof\QuestBundle\Entity\AchievementCategoryRepository")
 */
class AchievementCategory implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
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
    private $childs;

    /**
    * @ORM\OneToMany(targetEntity="Achievement", mappedBy="category")
    * @ORM\JoinColumn(nullable=true)
    */
    private $achievements;

    public function __construct(){
        $this->childs = new ArrayCollection();
        $this->achievements = new ArrayCollection();
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
     * Set icon
     *
     * @param string $icon
     * @return AchievementCategory
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return AchievementCategory
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return AchievementCategory
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
    * Set parent
    *
    * @param AchievementCategory $parent
    * @return AchievementCategory
    */
    public function setParent(AchievementCategory $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
    * Get parent
    *
    * @return AchievementCategory
    */
    public function getParent()
    {
        return $this->parent;
    }

    /**
    * Add childs
    *
    * @param AchievementCategory $childs
    * @return AchievementCategory
    */
    public function addChild(AchievementCategory $childs)
    {
        $this->childs[] = $childs;

        return $this;
    }

    /**
    * Remove childs
    *
    * @param AchievementCategory $childs
    * @return AchievementCategory
    */
    public function removeChild(AchievementCategory $childs)
    {
        $this->childs->removeElement($childs);

        return $this;
    }

    /**
    * Get childs
    *
    * @return Collection
    */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
    * Add achievements
    *
    * @param Achievement $achievements
    * @return AchievementCategory
    */
    public function addAchievement(Achievement $achievements)
    {
        $this->achievements[] = $achievements;

        return $this;
    }

    /**
    * Remove achievements
    *
    * @param Achievement $achievements
    * @return AchievementCategory
    */
    public function removeAchievement(Achievement $achievements)
    {
        $this->achievements->removeElement($achievements);

        return $this;
    }

    /**
    * Get achievements
    *
    * @return Collection
    */
    public function getAchievements()
    {
        return $this->achievements;
    }

    public function __toString(){
        return $this->nameFr;
    }
}
