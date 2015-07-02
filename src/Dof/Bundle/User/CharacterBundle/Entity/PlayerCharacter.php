<?php

namespace Dof\Bundle\User\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait;
use XN\DependencyInjection\RequireSecurityContextInterface;
use XN\DependencyInjection\RequireSecurityContextTrait;
use Dof\Bundle\GraphicsBundle\BasicPCLook;
use Dof\Bundle\CharacterBundle\Gender;
use Dof\Bundle\CharacterBundle\Entity\Breed;

/**
 * PlayerCharacter.
 *
 * @ORM\Table(name="dof_build_playercharacter")
 * @ORM\Entity(repositoryClass="Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacterRepository")
 */
class PlayerCharacter implements IdentifiableInterface, OwnableInterface, RequireSecurityContextInterface
{
    use TimestampableTrait, SluggableTrait, OwnableTrait, RequireSecurityContextTrait;

    /**
     * @var int
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
     * @var int
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
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->stuffs = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return PlayerCharacter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return PlayerCharacter
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
     * Add stuffs.
     *
     * @param Stuff $stuffs
     *
     * @return PlayerCharacter
     */
    public function addStuff(Stuff $stuffs)
    {
        $this->stuffs[] = $stuffs;

        return $this;
    }

    /**
     * Remove stuffs.
     *
     * @param Stuff $stuffs
     *
     * @return PlayerCharacter
     */
    public function removeStuff(Stuff $stuffs)
    {
        $this->stuffs->removeElement($stuffs);

        return $this;
    }

    /**
     * Get stuffs.
     *
     * @return Collection
     */
    public function getStuffs()
    {
        return $this->stuffs;
    }

    /**
     * Set visible.
     *
     * @param bool $visible
     *
     * @return Stuff
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    public function getLook()
    {
        $bpcl = new BasicPCLook();
        $bpcl
            ->setBreed($this->getBreed())
            ->setGender(Gender::MALE)
            ->setFace($this->getBreed()->getFace(Gender::MALE, 'I'))
            ->setColors($this->getBreed()->getMaleDefaultColors())
        ;

        return $bpcl;
    }

    public function getEntityLook()
    {
        return $this->getLook()->toEntityLook();
    }

    public function canSee()
    {
        return $this->isVisible() || $this->canWrite();
    }

    public function canWrite()
    {
        return $this->sc->isGranted('ROLE_ADMIN') || $this->getCurrentUser() === $this->getOwner();
    }

    public function getCurrentUser()
    {
        return ($token = $this->sc->getToken()) !== null ?
            is_object($user = $token->getUser()) ? $user : null
        : null;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
