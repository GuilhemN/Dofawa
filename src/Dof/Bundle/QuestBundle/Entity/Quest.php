<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

use Dof\Bundle\CMSBundle\Entity\QuestArticle;

/**
 * Quest
 *
 * @ORM\Table(name="dof_quests")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\QuestRepository")
 */
class Quest implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isRepeatable", type="boolean")
     */
    private $isRepeatable;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isDungeonQuest", type="boolean")
     */
    private $isDungeonQuest;

    /**
     * @var integer
     *
     * @ORM\Column(name="levelMin", type="integer")
     */
    private $levelMin;

    /**
     * @var integer
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
    * @var boolean
    *
    * @ORM\Column(name="season", type="boolean", nullable=true)
    */
    private $season;

    /**
    * @var QuestArticle
    *
    * @ORM\OneToOne(targetEntity="Dof\Bundle\CMSBundle\Entity\QuestArticle", mappedBy="quest")
    */
    private $article;

    /**
    * Set id
    *
    * @return Quest
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
     * Set isRepeatable
     *
     * @param boolean $isRepeatable
     * @return Quest
     */
    public function setIsRepeatable($isRepeatable)
    {
        $this->isRepeatable = $isRepeatable;

        return $this;
    }

    /**
     * Get isRepeatable
     *
     * @return boolean
     */
    public function getIsRepeatable()
    {
        return $this->isRepeatable;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Quest
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isDungeonQuest
     *
     * @param boolean $isDungeonQuest
     * @return Quest
     */
    public function setIsDungeonQuest($isDungeonQuest)
    {
        $this->isDungeonQuest = $isDungeonQuest;

        return $this;
    }

    /**
     * Get isDungeonQuest
     *
     * @return boolean
     */
    public function getIsDungeonQuest()
    {
        return $this->isDungeonQuest;
    }

    /**
     * Set levelMin
     *
     * @param integer $levelMin
     * @return Quest
     */
    public function setLevelMin($levelMin)
    {
        $this->levelMin = $levelMin;

        return $this;
    }

    /**
     * Get levelMin
     *
     * @return integer
     */
    public function getLevelMin()
    {
        return $this->levelMin;
    }

    /**
     * Set levelMax
     *
     * @param integer $levelMax
     * @return Quest
     */
    public function setLevelMax($levelMax)
    {
        $this->levelMax = $levelMax;

        return $this;
    }

    /**
     * Get levelMax
     *
     * @return integer
     */
    public function getLevelMax()
    {
        return $this->levelMax;
    }

    /**
    * Set category
    *
    * @param QuestCategory $category
    * @return Quest
    */
    public function setCategory(QuestCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
    * Get category
    *
    * @return QuestCategory
    */
    public function getCategory()
    {
        return $this->category;
    }
    /**
    * Add steps
    *
    * @param QuestStep $steps
    * @return Quest
    */
    public function addStep(QuestStep $steps)
    {
        $this->steps[] = $steps;

        return $this;
    }

    /**
    * Remove steps
    *
    * @param QuestStep $steps
    * @return Quest
    */
    public function removeStep(QuestStep $steps)
    {
        $this->steps->removeElement($steps);

        return $this;
    }

    /**
    * Get steps
    *
    * @return Collection
    */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
    * Set season
    *
    * @param boolean $season
    * @return Quest
    */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
    * Get season
    *
    * @return boolean
    */
    public function getSeason()
    {
        return $this->season;
    }

    /**
    * Set article
    *
    * @param QuestArticle $article
    * @return Quest
    */
    public function setArticle(QuestArticle $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
    * Get article
    *
    * @return QuestArticle
    */
    public function getArticle()
    {
        return $this->article;
    }

    public function __toString(){
        return $this->nameFr;
    }
}
