<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * Achievement.
 *
 * @ORM\Table(name="dof_achievements")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\AchievementRepository")
 */
class Achievement implements IdentifiableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, LocalizedDescriptionTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var AchievementCategory
     *
     * @ORM\ManyToOne(targetEntity="AchievementCategory", inversedBy="achievements")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * @var int
     *
     * @ORM\Column(name="iconId", type="integer")
     */
    private $iconId;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var float
     *
     * @ORM\Column(name="kamasRatio", type="float")
     */
    private $kamasRatio;

    /**
     * @var float
     *
     * @ORM\Column(name="xpRatio", type="float")
     */
    private $xpRatio;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

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
     * Set category.
     *
     * @param AchievementCategory $category
     *
     * @return Achievement
     */
    public function setCategory(AchievementCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return AchievementCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set order.
     *
     * @param int $order
     *
     * @return Achievement
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
     * Set iconId.
     *
     * @param int $iconId
     *
     * @return Achievement
     */
    public function setIconId($iconId)
    {
        $this->iconId = $iconId;

        return $this;
    }

    /**
     * Get iconId.
     *
     * @return int
     */
    public function getIconId()
    {
        return $this->iconId;
    }

    /**
     * Set points.
     *
     * @param int $points
     *
     * @return Achievement
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points.
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return Achievement
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set kamasRatio.
     *
     * @param float $kamasRatio
     *
     * @return Achievement
     */
    public function setKamasRatio($kamasRatio)
    {
        $this->kamasRatio = $kamasRatio;

        return $this;
    }

    /**
     * Get kamasRatio.
     *
     * @return float
     */
    public function getKamasRatio()
    {
        return $this->kamasRatio;
    }

    /**
     * Set xpRatio.
     *
     * @param float $xpRatio
     *
     * @return Achievement
     */
    public function setXpRatio($xpRatio)
    {
        $this->xpRatio = $xpRatio;

        return $this;
    }

    /**
     * Get xpRatio.
     *
     * @return float
     */
    public function getXpRatio()
    {
        return $this->xpRatio;
    }

    public function __toString()
    {
        return $this->name;
    }
}
