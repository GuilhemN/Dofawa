<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use Dof\Bundle\ItemBundle\Criteria\ParsedCriteriaTrait;
use Dof\Bundle\ItemBundle\Criteria\ParsedCriteriaInterface;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;

/**
 * MonsterDrop.
 *
 * @ORM\Table(name="dof_monster_drops")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MonsterBundle\Entity\MonsterDropRepository")
 */
class MonsterDrop implements IdentifiableInterface, ParsedCriteriaInterface
{
    use TimestampableTrait, ParsedCriteriaTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Monster
     *
     * @Groups({"item"})
     * @ORM\ManyToOne(targetEntity="Monster", inversedBy="drops")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $monster;

    /**
     * @var ItemTemplate
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\ItemTemplate", inversedBy="drops")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $object;

    /**
     * @var int
     *
     * @ORM\Column(name="index_", type="integer")
     */
    private $index;

    /**
     * @var float
     *
     * @Groups({"item"})
     * @ORM\Column(name="minPercent", type="float")
     */
    private $minPercent;

    /**
     * @var float
     *
     * @Groups({"item"})
     * @ORM\Column(name="maxPercent", type="float")
     */
    private $maxPercent;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var int
     *
     * @Groups({"item"})
     * @ORM\Column(name="has_criteria", type="boolean")
     */
    private $hasCriteria;

    /**
     * @var string
     *
     * @ORM\Column(name="criteria", type="string", length=127, nullable=true)
     */
    private $criteria;

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
     * Set monster.
     *
     * @param Monster $monster
     *
     * @return MonsterGrade
     */
    public function setMonster(Monster $monster)
    {
        $this->monster = $monster;

        return $this;
    }

    /**
     * Get monster.
     *
     * @return Monster
     */
    public function getMonster()
    {
        return $this->monster;
    }

    /**
     * Set object.
     *
     * @param ItemTemplate $object
     *
     * @return MonsterDrop
     */
    public function setObject(ItemTemplate $object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object.
     *
     * @return ItemTemplate
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set index.
     *
     * @param int $index
     *
     * @return MonsterDrop
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index.
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set minPercent.
     *
     * @param float $minPercent
     *
     * @return MonsterDrop
     */
    public function setMinPercent($minPercent)
    {
        $this->minPercent = $minPercent;

        return $this;
    }

    /**
     * Get minPercent.
     *
     * @return float
     */
    public function getMinPercent()
    {
        return $this->minPercent;
    }

    /**
     * Set maxPercent.
     *
     * @param float $maxPercent
     *
     * @return MonsterDrop
     */
    public function setMaxPercent($maxPercent)
    {
        $this->maxPercent = $maxPercent;

        return $this;
    }

    /**
     * Get maxPercent.
     *
     * @return float
     */
    public function getMaxPercent()
    {
        return $this->maxPercent;
    }

    /**
     * Set count.
     *
     * @param int $count
     *
     * @return MonsterDrop
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count.
     *
     * @return int
     */
    public function getCount()
    {
        return min(16, $this->count);
    }

    /**
     * Set hasCriteria.
     *
     * @param bool $hasCriteria
     *
     * @return MonsterDrop
     */
    public function setHasCriteria($hasCriteria)
    {
        $this->hasCriteria = $hasCriteria;

        return $this;
    }

    /**
     * Get hasCriteria.
     *
     * @return bool
     */
    public function getHasCriteria()
    {
        return $this->hasCriteria;
    }

    /**
     * Get hasCriteria.
     *
     * @return bool
     */
    public function hasCriteria()
    {
        return $this->hasCriteria;
    }

    /**
     * Set criteria.
     *
     * @param string $criteria
     *
     * @return MonsterDrop
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * Get criteria.
     *
     * @return string
     */
    public function getCriteria()
    {
        return $this->criteria;
    }
}
