<?php

namespace Dof\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

use Dof\CharactersBundle\Entity\Breed;
use Dof\BuildBundle\Entity\Stuff;

/**
 * PlayerCharacter
 *
 * @ORM\Table(name="dof_build_playercharacter")
 * @ORM\Entity(repositoryClass="Dof\BuildBundle\Entity\PlayerCharacterRepository")
 */
class PlayerCharacter implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface
{
    use TimestampableTrait, SluggableTrait, OwnableTrait;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer", nullable=false)1
     * @Assert\Range(
     *      min = 1,
     *      max = 200,
     *      minMessage = "Le level doit être compris entre 1 et 200.",
     *      maxMessage = "Le level doit être compris entre 1 et 200."
     * )
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\CharactersBundle\Entity\Breed")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $breed;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="character")
     */
    private $stuffs;

    public function __construct()
    {
        $this->stuffs = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return PlayerCharacter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return PlayerCharacter
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    public function setBreed(Breed $breed)
    {
        $this->breed = $breed;
        return $this;
    }

    public function getBreed()
    {
        return $this->breed;
    }

    /**
     * Add stuffs
     *
     * @param Stuff $stuffs
     * @return PlayerCharacter
     */
    public function addStuff(Stuff $stuffs)
    {
        $this->stuffs[] = $stuffs;

        return $this;
    }

    /**
     * Remove stuffs
     *
     * @param Stuff $stuffs
     * @return PlayerCharacter
     */
    public function removeStuff(Stuff $stuffs)
    {
        $this->stuffs->removeElement($stuffs);

        return $this;
    }

    /**
     * Get stuffs
     *
     * @return Collection
     */
    public function getStuffs()
    {
        return $this->stuffs;
    }

    public function __toString()
    {
      return $this->getName();
    }
}
