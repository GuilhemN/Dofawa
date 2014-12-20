<?php

namespace Dof\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * MonsterRace
 *
 * @ORM\Table(name="dof_monster_races")
 * @ORM\Entity(repositoryClass="Dof\MonsterBundle\Entity\MonsterRaceRepository")
 */
class MonsterRace implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
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
    * @var Monster
    *
    * @ORM\ManyToOne(targetEntity="MonsterSuperRace")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $parent;

    /**
    * @ORM\OneToMany(targetEntity="Monster", mappedBy="race")
    * @ORM\JoinColumn(nullable=true)
    */
    private $monsters;


    public function __construct()
    {
        $this->monsters = new ArrayCollection();
    }

    /**
    * Set id
    *
    * @param integer $id
    * @return MonsterRace
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
    * Set parent
    *
    * @param MonsterSuperRace $parent
    * @return MonsterRace
    */
    public function setParent(MonsterSuperRace $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
    * Get parent
    *
    * @return MonsterSuperRace
    */
    public function getParent()
    {
        return $this->parent;
    }
    /**
    * Add monsters
    *
    * @param Monster $monsters
    * @return MonsterRace
    */
    public function addMonster(Monster $monsters)
    {
        $this->monsters[] = $monsters;

        return $this;
    }

    /**
    * Remove monsters
    *
    * @param Monster $monsters
    * @return MonsterRace
    */
    public function removeMonster(Monster $monsters)
    {
        $this->monsters->removeElement($monsters);

        return $this;
    }

    /**
    * Get monsters
    *
    * @return Collection
    */
    public function getMonsters()
    {
        return $this->monsters;
    }
}
