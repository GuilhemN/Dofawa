<?php

namespace Dof\Bundle\User\CharacterBundle\Entity;

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
use Dof\Bundle\UserBundle\OwnableTrait;

use Dof\Bundle\GraphicsBundle\BasicPCLook;
use Dof\Bundle\CharacterBundle\Gender;

use Dof\Bundle\CharacterBundle\Entity\Breed;
use Dof\Bundle\User\CharacterBundle\Entity\Stuff;

/**
 * PlayerCharacter
 *
 * @ORM\Table(name="dof_build_playercharacter")
 * @ORM\Entity(repositoryClass="Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacterRepository")
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
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\CharacterBundle\Entity\Breed")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $breed;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\Stuff", mappedBy="character")
     */
    private $stuffs;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

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

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Stuff
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    public function getLook() {
        $bpcl = new BasicPCLook();
        $bpcl
            ->setBreed($this->getBreed())
            ->setGender(Gender::MALE)
            ->setFace($this->getBreed()->getFace(Gender::MALE, 'I'))
            ->setColors($this->getBreed()->getMaleDefaultColors())
        ;
        return $bpcl;
    }

    public function getEntityLook() {
        return $this->getLook()->toEntityLook();
    }

    public function __toString()
    {
      return $this->getName();
    }
}
