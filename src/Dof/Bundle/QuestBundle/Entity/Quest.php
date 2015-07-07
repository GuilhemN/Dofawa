<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * Quest.
 *
 * @ORM\Table(name="dof_quests")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\QuestRepository")
 */
class Quest implements IdentifiableInterface, LocalizedNameInterface
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
     * @var bool
     *
     * @ORM\Column(name="isRepeatable", type="boolean")
     */
    private $isRepeatable;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="isDungeonQuest", type="boolean")
     */
    private $isDungeonQuest;

    /**
     * @var int
     *
     * @ORM\Column(name="levelMin", type="integer")
     */
    private $levelMin;

    /**
     * @var int
     *
     * @ORM\Column(name="levelMax", type="integer")
     */
    private $levelMax;

    /**
     * @var QuestCategory
     *
     * @ORM\ManyToOne(targetEntity="QuestCategory", inversedBy="quests")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="QuestStep", mappedBy="quest")
     * @ORM\JoinColumn(nullable=true)
     */
    private $steps;

    /**
     * @var bool
     *
     * @ORM\Column(name="season", type="boolean", nullable=true)
     */
    private $season;

    /**
     * Set id.
     *
     * @return Quest
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
     * Set isRepeatable.
     *
     * @param bool $isRepeatable
     *
     * @return Quest
     */
    public function setIsRepeatable($isRepeatable)
    {
        $this->isRepeatable = $isRepeatable;

        return $this;
    }

    /**
     * Get isRepeatable.
     *
     * @return bool
     */
    public function getIsRepeatable()
    {
        return $this->isRepeatable;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Quest
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isDungeonQuest.
     *
     * @param bool $isDungeonQuest
     *
     * @return Quest
     */
    public function setIsDungeonQuest($isDungeonQuest)
    {
        $this->isDungeonQuest = $isDungeonQuest;

        return $this;
    }

    /**
     * Get isDungeonQuest.
     *
     * @return bool
     */
    public function getIsDungeonQuest()
    {
        return $this->isDungeonQuest;
    }

    /**
     * Set levelMin.
     *
     * @param int $levelMin
     *
     * @return Quest
     */
    public function setLevelMin($levelMin)
    {
        $this->levelMin = $levelMin;

        return $this;
    }

    /**
     * Get levelMin.
     *
     * @return int
     */
    public function getLevelMin()
    {
        return $this->levelMin;
    }

    /**
     * Set levelMax.
     *
     * @param int $levelMax
     *
     * @return Quest
     */
    public function setLevelMax($levelMax)
    {
        $this->levelMax = $levelMax;

        return $this;
    }

    /**
     * Get levelMax.
     *
     * @return int
     */
    public function getLevelMax()
    {
        return $this->levelMax;
    }

    /**
     * Set category.
     *
     * @param QuestCategory $category
     *
     * @return Quest
     */
    public function setCategory(QuestCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return QuestCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Add steps.
     *
     * @param QuestStep $steps
     *
     * @return Quest
     */
    public function addStep(QuestStep $steps)
    {
        $this->steps[] = $steps;

        return $this;
    }

    /**
     * Remove steps.
     *
     * @param QuestStep $steps
     *
     * @return Quest
     */
    public function removeStep(QuestStep $steps)
    {
        $this->steps->removeElement($steps);

        return $this;
    }

    /**
     * Get steps.
     *
     * @return Collection
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Set season.
     *
     * @param bool $season
     *
     * @return Quest
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season.
     *
     * @return bool
     */
    public function getSeason()
    {
        return $this->season;
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
