<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * MonsterRace.
 *
 * @ORM\Table(name="dof_monster_races")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MonsterBundle\Entity\MonsterRaceRepository")
 */
class MonsterRace implements IdentifiableInterface
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
     * @var Monster
     *
     * @ORM\ManyToOne(targetEntity="MonsterSuperRace", inversedBy="children")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Monster", mappedBy="race")
     */
    private $monsters;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->monsters = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return MonsterRace
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
     * Set parent.
     *
     * @param MonsterSuperRace $parent
     *
     * @return MonsterRace
     */
    public function setParent(MonsterSuperRace $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return MonsterSuperRace
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Add monsters.
     *
     * @param Monster $monsters
     *
     * @return MonsterRace
     */
    public function addMonster(Monster $monsters)
    {
        $this->monsters[] = $monsters;

        return $this;
    }

    /**
     * Remove monsters.
     *
     * @param Monster $monsters
     *
     * @return MonsterRace
     */
    public function removeMonster(Monster $monsters)
    {
        $this->monsters->removeElement($monsters);

        return $this;
    }

    /**
     * Get monsters.
     *
     * @return Collection
     */
    public function getMonsters()
    {
        return $this->monsters;
    }
}
